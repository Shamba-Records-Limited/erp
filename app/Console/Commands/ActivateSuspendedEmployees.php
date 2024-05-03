<?php

namespace App\Console\Commands;

use App\Http\Traits\Employee;
use Illuminate\Console\Command;

class ActivateSuspendedEmployees extends Command
{
    use Employee;

    protected $signature = 'disciplinary:activate-employee';
    protected $description = 'Activate Suspended Employees';

    public function handle()
    {
        $this->activate_suspended_users();
    }
}
