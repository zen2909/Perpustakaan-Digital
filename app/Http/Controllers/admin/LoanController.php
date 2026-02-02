<?php

namespace App\Http\Controllers\admin;

use App\Models\Author;
use App\Models\Book;
use App\Models\Loan;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\LoanService;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Exists;
use function Laravel\Prompts\error;
use function PHPUnit\Framework\isEmpty;

class LoanController extends Controller
{

    public function __construct(protected LoanService $loanservice)
    {
    }

    public function index()
    {
        $query = Loan::query()
            ->with(['book']);

        $loan = $query->latest()->paginate(10)->withQueryString();
        return view('member.my-loans', [
            'loans' => $loan,
            'authors' => Author::all(),
        ]);
    }

    public function loan()
    {
        $query = Loan::query()
            ->with(['user', 'book']);

        $loan = $query->latest()->paginate(10)->withQueryString();
        return view('admin.approval', ['loans' => $loan]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $bookloan = Book::findOrFail($request->book_id);

        if (Loan::where('user_id', auth()->id())->where('book_id', $request->book_id)->whereIn('status', ['pending', 'approved', 'borrowed', 'overdue'])->exists()) {
            return back()->with('error', 'Buku sudah dipinjam');
        }

        if ($bookloan->available_stock <= 0) {
            return back()->with('error', 'Stok buku habis');
        }

        Loan::create([
            'user_id' => Auth()->id(),
            'book_id' => $bookloan->id,
            'loan_date' => null,
            'due_date' => null,
            'return_date' => null,
            'status' => 'pending',
            'approved_by' => null,
            'fine_amount' => 0,
            'qr_token' => Str::uuid(),
        ]);
        return redirect()->route('members.loan')->with('success', 'Permintaan peminjaman berhasil dikirim, menunggu approval admin');

    }


    public function scanQr(Request $request)
    {
        $request->validate([
            'qr_token' => 'required|string|max:40',
        ]);

        $qr_token = $request->qr_token;

        $data = Loan::where('qr_token', '=', $qr_token)->first();

        if ($data == null) {
            return response('data peminjaman tidak ditemukan');
        }

        if ($data->status == 'approved') {
            $this->loanservice->borrow($data);
            return response('QR valid');
        } else {
            return response('QR tidak valid');
        }

    }

    public function destroy(Loan $loan)
    {
        $loan->delete();

        return redirect()->route('admin.loans.index')->with('success', 'Data Peminjaman berhasil dihapus');
    }


}