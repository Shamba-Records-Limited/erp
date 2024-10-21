<?php

namespace App\Console\Commands;

use App\Sale;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateInvoiceNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:generateInvoiceNo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate InvoiceNumber for existing invoice';

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
        $cooperativeIds = DB::select("select distinct (cooperative_id)  from sales");
        $this->updateInvoiceNumbers($cooperativeIds);
        $this->updateSaleBatchNumber($cooperativeIds);
        return 0;
    }

    private function updateInvoiceNumbers($cooperativeIds)
    {
        foreach ($cooperativeIds as $cooperative) {
            $sales = Sale::withTrashed()->where('cooperative_id', $cooperative->cooperative_id)->orderBy('created_at')->get();
            $count = 0;
            foreach ($sales as $sale) {
                if ($sale->invoices) {
                    $invoice_number = Carbon::parse($sale->invoices->date)->format('Ymd');
                    $invoice_count = ++$count;
                    $sale->invoices->invoice_number = $invoice_number;
                    $sale->invoices->invoice_count = $invoice_count;
                    $sale->invoices->save();
                } else {
                    echo "saleId: {$sale->id} Has no invoice\n";
                }
            }
        }
        echo "Invoice update done\n";
    }

    private function updateSaleBatchNumber($cooperativeIds)
    {
        foreach ($cooperativeIds as $cooperative) {
            $sales = Sale::withTrashed()->where('cooperative_id', $cooperative->cooperative_id)->orderBy('created_at')->get();
            $count = 0;
            foreach ($sales as $sale) {
                $invoice_number = Carbon::parse($sale->date)->format('Ymd');
                $invoice_count = ++$count;
                $sale->sale_batch_number = $invoice_number;
                $sale->sale_count = $invoice_count;
                $sale->save();
            }
        }
        echo "Sales update done\n";
    }
}
