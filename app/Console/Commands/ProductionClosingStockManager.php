<?php

namespace App\Console\Commands;

use App\Http\Traits\Manufacturing;
use Illuminate\Console\Command;
use Log;

class ProductionClosingStockManager extends Command
{

    use Manufacturing;

    protected $signature = 'manufacturing:closing-stock-manager';

    protected $description = 'Update Production Closing Stock';

    public function handle()
    {
        $this->update_daily_closing_stock();
    }
}
