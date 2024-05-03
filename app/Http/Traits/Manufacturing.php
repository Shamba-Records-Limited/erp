<?php

namespace App\Http\Traits;

use App\Production;
use App\ProductionHistory;
use App\ProductionStockTracker;
use Carbon\Carbon;
use DB;
use Exception;
use Log;

trait Manufacturing
{

    private function update_daily_opening_stock()
    {
        try {
            DB::beginTransaction();
            Log::info("Starting stock opening stock update at " . date('Y-m-d H:i:s'));
            $productions = Production::all();

            foreach ($productions as $p) {
                $today = Carbon::now(env('TIMEZONE'))->format('Y-m-d');
                $exists = ProductionStockTracker::whereDate('date', '=', $today)->count() > 0;

                if (!$exists) {
                    $stock = new ProductionStockTracker();
                    $stock->final_product_id = $p->finalProduct->id;
                    $stock->selling_price = $p->finalProduct->selling_price;
                    $stock->date = $today;
                    $stock->opening_quantity = $p->available_quantity;
                    $stock->opening_stock_value = $p->available_quantity * $p->finalProduct->selling_price;
                    $stock->closing_stock = 0;
                    $stock->closing_stock_value = 0;
                    $stock->cooperative_id = $p->cooperative_id;
                    $stock->created_at = Carbon::now(env('TIMEZONE'));
                    $stock->updated_at = null;
                    $stock->save();
                    DB::commit();
                }
                Log::info("Stock opening stock update completed at " . date('Y-m-d H:i:s'));
            }
        } catch (Exception $e) {
            Log::error("Failed to do opening stock update");
            DB::rollBack();
        }

    }

    private function update_daily_closing_stock()
    {

        try {
            DB::beginTransaction();
            Log::info("Starting closing stock update at " . date('Y-m-d H:i:s'));

            $stocks = ProductionStockTracker::where('closing_stock', 0)
                ->where('closing_stock_value', 0)
                ->whereDate('date', Carbon::now(env('TIMEZONE'))->format('Y-m-d'))
                ->get();

            foreach ($stocks as $stock) {
                $production = Production::where('final_product_id', $stock->final_product_id)
                    ->first();
                if ($production) {
                    $stock->closing_stock = $production->available_quantity;
                    $stock->closing_stock_value =
                        $production->finalProduct->selling_price * $production->available_quantity;
                    $stock->updated_at = Carbon::now(env('TIMEZONE'));;
                    $stock->save();
                }

            }
            DB::commit();
            Log::info("Stock closing stock update completed at " . date('Y-m-d H:i:s'));

        } catch (Exception $e) {
            Log::error("{$e->getMessage()}: Failed to do opening stock update");
            DB::rollBack();
        }

    }

    public function check_expired_goods()
    {
        Log::info("Checking expired production items");
        $today = Carbon::now(env('TIMEZONE'))->format('Y-m-d');
        $expired_goods = ProductionHistory::where('expires', 1)
            ->whereDate('expiry_date', '=', $today)
            ->where('expiry_status', ProductionHistory::EXPIRY_STATUS_NOT_EXPIRED)
            ->get();
        try {
            DB::beginTransaction();
            foreach ($expired_goods as $item) {
                $production = Production::findOrFail($item->production_id);
                $production->available_quantity -= $item->quantity;
                $production->save();
                $item->expiry_status = ProductionHistory::EXPIRY_STATUS_EXPIRED;
                $item->save();
            }
            DB::commit();

        } catch (Exception $e) {
            Log::error("{$e->getMessage()}: Failed to do opening stock update");
            DB::rollBack();
        }

        Log::info(count($expired_goods) . " Items expired");


    }

}
