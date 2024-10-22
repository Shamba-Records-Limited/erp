<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\LoanAutoRepay;

class LoanRepay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loan:repay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto repay from wallet';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $repayJob = new LoanAutoRepay();
        $this->dispatch($repayJob);
        return 0;
    }
}
