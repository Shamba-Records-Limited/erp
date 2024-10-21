<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FarmerCalendarExport implements WithMultipleSheets
{
 private $user;
 
 public function __construct(User $user)
 {
    $this->user = $user;
 }

 public function sheets(): array
 {
    return [
        new FarmerCropCalendarsExport($this->user),
        new FarmerLivestockorPoultryCalExport($this->user)
    ];
 }
}
