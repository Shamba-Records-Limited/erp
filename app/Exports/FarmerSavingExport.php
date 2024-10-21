<?php

namespace App\Exports;

use App\SavingAccount;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FarmerSavingExport implements FromCollection, WithMapping, WithHeadings
{
    private $farmer_id;

    public function __construct($farmer_id)
    {
        $this->farmer_id = $farmer_id;
    }

    public function collection(): \Illuminate\Support\Collection
    {
        $active_saving = SavingAccount::STATUS_ACTIVE;
        $savings = DB::select("SELECT sa.id, sa.amount, sa.maturity_date, st.type AS type
                                FROM saving_accounts sa JOIN saving_types st ON sa.saving_type_id = st.id
                                WHERE sa.farmer_id = '$this->farmer_id' AND sa.status = '$active_saving'");
        return collect($savings);
    }

    public function headings(): array
    {
        return [
            'Saving ID',
            'Saving Type',
            'Amount',
            'Maturity Date',
        ];
    }

    public function map($row): array
    {
        return [
            sprintf("%03d", $row->id),
            $row->type,
            $row->amount,
            $row->maturity_date
        ];
    }
}
