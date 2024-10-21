<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ManufacturingStore implements FromCollection, WithHeadings,WithMapping
{
    private $cooperative;

    public function __construct($cooperative){
        $this->cooperative = $cooperative;
    }
    public function collection()
    {
        return \App\ManufacturingStore::where('cooperative_id', $this->cooperative)->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Name',
            'Location'
        ];
    }

    public function map($row): array
    {
       return [
           $row->name,
           $row->location
       ];
    }
}
