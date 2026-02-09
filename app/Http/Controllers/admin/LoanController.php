<?php

namespace App\Http\Controllers\admin;

use App\Models\Author;
use App\Models\Book;
use App\Models\Loan;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\LoanService;
use Auth;
use Dotenv\Exception\ValidationException;
use GrahamCampbell\ResultType\Success;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Exists;
use function Laravel\Prompts\error;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\throwException;

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
            'token_return' => null,
            'qr_path' => null,
        ]);
        return redirect()->route('members.loan')->with('success', 'Permintaan peminjaman berhasil dikirim, menunggu approval admin');

    }

    public function approve(Loan $loan)
    {
        $this->loanservice->approve($loan, auth()->id());
        $this->loanservice->sendApprovalMail($loan);

        return back()->with('success', 'Peminjaman Telah di Konfirmasi.');
    }

    public function scanLoan(Request $request)
    {
        $request->validate([
            'qr_token' => 'required|string|max:255',
        ]);

        $qr_token = $request->qr_token;

        $data = Loan::where('qr_token', '=', $qr_token)->first();

        if ($data == null) {
            return response()->json([
                'success' => false,
                'message' => 'Data peminjaman tidak ditemukan',
            ], 404);
        }

        try {
            $this->loanservice->borrow($data);
        } catch (ValidationException $th) {
            return response()->json([
                'success' => false,
                'message' => 'Qr Tidak Valid',
                'errors' => $th->errors(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'QR Valid'
        ], 200);

    }
    public function scanReturn(Request $request)
    {
        $request->validate([
            'token_return' => 'required|string|max:255',
        ]);

        $token_return = $request->token_return;

        $data = Loan::where('token_return', '=', $token_return)->first();

        if ($data == null) {
            return response()->json([
                'success' => false,
                'message' => 'Data peminjaman tidak ditemukan',
            ], 404);
        }

        try {
            $this->loanservice->returnBook($data);
        } catch (ValidationException $th) {
            return response()->json([
                'success' => false,
                'message' => 'Qr Tidak Valid',
                'errors' => $th->errors(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'QR Valid'
        ], 200);

    }

    public function markAsOverdue(Loan $loan): RedirectResponse
    {
        foreach ($loan as $loan => $index) {
            $this->loanservice->markAsOverdue($loan);
        }
    }

    public function destroy(Loan $loan)
    {
        $loan->delete();

        return redirect()->route('admin.loans.index')->with('success', 'Data Peminjaman berhasil dihapus');
    }


}