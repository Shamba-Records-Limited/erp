<?php

namespace App\Imports\CooperativeAdmin;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

use App\Collection as Collections;
use App\CoopBranch;
use App\Farmer;
use App\Lot;
use App\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

HeadingRowFormatter::extend('custom', function ($value, $key) {
    return str_replace(" ", "_", $value);
});

class CollectionImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Collections;

    /**
     * @throws UnableToCreateEmployeeException
     * @throws \Throwable
     */
    public function collection(Collection $rows)
    {
        $authUser = Auth::user();
        $coop_id = $authUser->cooperative_id;

        DB::beginTransaction();
        foreach ($rows as $row) {
            // creating lot
            $now = Carbon::now();
            $now_str = strtoupper($now->format('Ymd'));
            $date_str = $now->format('Y-m-d')." 00:00:00";

            $dateAfter_str = $now->format('Y-m-d')." 23:59:59";

            $lot_count = Lot::where('created_at', '>=', $date_str)
                ->where('created_at', '<', $dateAfter_str)
                ->count();

            $lot_ind = $lot_count + 1;

            $lot_number =  'LOT'.$now_str.$lot_ind;
            try{
                $lot = Lot::where('cooperative_id', $coop_id)
                    ->where('created_at', '<', $dateAfter_str)
                    ->where('created_at', '>=', $date_str)
                    ->firstOrFail();
            } catch (\Throwable $th) {
                $lot = new Lot();
                $lot->cooperative_id = $coop_id;
                $lot->lot_number = $lot_number;
                $lot->available_quantity = $row["quantity"];
                $lot->save();
            }

            $lot->available_quantity += floatval($row["quantity"]);
            $lot->save();

            // creating collection number
            $collection_count = Collections::where('cooperative_id', $coop_id)
                ->where('lot_number', $lot->lot_number)
                ->count();

            $collection_ind = $collection_count + 1;

            $collection_number = 'COL'.$now_str.$collection_ind;

            // load coop branch id
            $coop_branch_id = CoopBranch::where('name', $row["coop_branch"])->firstOrFail()->id;
            // load farmer id
            $farmer_id = Farmer::where('id_no', $row["farmer_id_no"])->firstOrFail()->id;
            // load product id
            $product_id = Product::where('name', $row["product"])->firstOrFail()->id;

            // creating collection
            $collection = new Collections();
            $collection->lot_number = $lot->lot_number;
            $collection->collection_number = $collection_number;
            $collection->cooperative_id = $coop_id;
            $collection->coop_branch_id = $coop_branch_id;
            $collection->farmer_id = $farmer_id;
            $collection->product_id = $product_id;
            $collection->quantity = $row["quantity"];
            $collection->collection_time = $row["collection_time"];
            $collection->comments = $row["comments"];
            $collection->date_collected = Carbon::now();
            $collection->save();
        }

        DB::commit();

    }

    public function rules(): array
    {
        $user = Auth::user();
        return [
            'coop_branch' => ['required', 'exists:coop_branches,name'],
            'farmer_id_no' => ['required', 'exists:farmers,id_no'],
            'product' => ['required', 'exists:products,name'],
            'quantity' => ['required', 'numeric', 'min:1'],
            'collection_time' => ['required'],
        ];
    }
}