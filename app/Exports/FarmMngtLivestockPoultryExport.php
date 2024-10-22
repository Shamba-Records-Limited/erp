<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FarmMngtLivestockPoultryExport implements WithMultipleSheets
{
    private $cooperativeId;

    public function __construct($cooperativeId)
    {
        $this->cooperativeId = $cooperativeId;
    }

    public function sheets(): array
    {
        return [
            new FarmMngtApprovedLivestockExport($this->cooperativeId),
            new FarmMngtLivestockExport($this->cooperativeId)
        ];
    }
    
}
