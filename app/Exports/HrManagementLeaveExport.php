<?php

namespace App\Exports;

use App\EmployeeLeave;
use EloquentBuilder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class HrManagementLeaveExport implements FromCollection, WithHeadings, WithMapping
{
    private $cooperativeId;

    public function __construct($cooperativeId)
    {
        $this->cooperativeId = $cooperativeId;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $leaves = EloquentBuilder::to(EmployeeLeave::whereHas('employee', function ($query) {
            $query->whereHas('user', function ($query2) {
                $query2->where('cooperative_id', $this->cooperativeId);
            });
        }), request()->all())->latest()->get();
        return collect($leaves);
    }
    public function headings(): array
    {
        return [
            'Employee',
            'Date',
            'Status',
            'Reason',
            'Remarks'
        ];
    }

    public function map($leave): array
    {
        $status = ($leave->status === 0) ? "Pending" : (($leave->status === 1) ? "Accepted" : (($leave->status === 2) ? "Rejected" : "Completed"));

        return [
            "Name: " . $leave->employee->user->first_name . ";  " . "\r\n" . "Number: " . $leave->employee->employee_no,
            "From: " . $leave->start_date . ";  " . "\r\n" . "To: " . $leave->end_date,
            $status,
            $leave->reason,
            $leave->remarks
        ];
    }
}
