<?php

namespace App\Console\Commands;

use App\Http\Traits\Manufacturing;
use Illuminate\Console\Command;

class ProductionCheckExpiredItems extends Command
{
    use Manufacturing;

    protected $signature = 'manufacturing:check-expired-items';
    protected $description = 'Production check expired items';
    public function handle()
    {
        $this->check_expired_goods();
    }
}
