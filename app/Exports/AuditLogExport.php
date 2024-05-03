<?php

namespace App\Exports;

use App\AuditTrail;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AuditLogExport implements FromCollection, WithMapping, WithHeadings
{
    private $cooperative;
    private $request;


    public function __construct($cooperative, $request)
    {
        $this->cooperative = $cooperative;
        $this->request = $request;
    }

    public function collection()
    {
        return AuditTrail::auditTrails($this->request, $this->cooperative, true);
    }

    public function headings(): array
    {
        return [
            "Employee",
            "Activity",
            "Date"
        ];
    }

    public function map($auditTrail): array
    {
        return [
            ucwords(strtolower($auditTrail->user->first_name.' '.$auditTrail->user->other_names)),
            $auditTrail->activity,
            Carbon::parse($auditTrail->created_at)->format('Y-m-d')

        ];
    }
}
