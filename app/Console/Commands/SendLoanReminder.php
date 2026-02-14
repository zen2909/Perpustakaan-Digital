<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Loan;
use function PHPUnit\Framework\isEmpty;

class SendLoanReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loans:send-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $loans = Loan::where('status', 'borrowed')
            ->whereDate('due_date', now()->addDays(3))
            ->get();

        if ($loans == isEmpty()) {
            $this->info('No Loans Need Reminder');
            return;
        }


        foreach ($loans as $loan) {
            Mail::to($loan->user->email)
                ->send(new LoanReminderMail($loan));
            $this->info('Send Reminder Email');
        }
    }
}