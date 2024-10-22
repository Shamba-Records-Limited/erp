<?php

namespace App\Console\Commands;

use App\Http\Traits\Manufacturing;
use Illuminate\Console\Command;
use Log;

class ProductionOpeningStockManager extends Command
{

    use Manufacturing;
    protected $signature = 'manufacturing:opening-stock-manager';

    protected $description = 'Update Production Opening Stock';

    public function handle()
    {
        $this->update_daily_opening_stock();
    }
}
