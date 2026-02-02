<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Loan;
use Illuminate\Support\Facades\Mail;
use App\Mail\LoanOverdueMail;
use App\Services\LoanService;

class CheckOverdueLoans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loan:check-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and mark overdue loans';

    public function __construct(protected LoanService $loanService)
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $loans = Loan::query()
            ->where('status', 'borrowed')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->whereNull('return_date')
            ->get();

        if ($loans->isEmpty()) {
            $this->info('No overdue loans found.');
            return;
        }

        foreach ($loans as $loan) {
            try {
                $this->loanService->markAsOverdue($loan);
                Mail::to($loan->user->email)
                    ->send(new LoanOverdueMail($loan));
                $loan->update(['overdue_notified_at' => now()]);
                $this->info("Loan ID {$loan->id} marked as overdue.");
            } catch (\Throwable $th) {
                $this->error("Loan ID {$loan->id} failed: {$th->getMessage()}");
            }
        }
    }
}