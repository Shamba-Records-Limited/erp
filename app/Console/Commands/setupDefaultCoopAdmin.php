<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class setupDefaultCoopAdmin extends Command
{
    protected $signature = 'create:coopadmin';

    protected $description = 'Create default cooperative admin user';

    public function handle()
    {
        echo 'Creating Default Coop Admin User....';
        User::setup_default_admin();
        return 0;
    }
}
