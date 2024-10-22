<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VetExtensionServicesVetsExport implements FromCollection, WithMapping, WithHeadings
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
        $vets = User::select("users.first_name", 'users.other_names', 'users.id', 'users.email', 'users.username')
        ->join("model_has_roles", "model_has_roles.model_id", "users.id")
        ->join("roles", "roles.id", "model_has_roles.role_id")
        ->where("roles.name", "vet")
        ->where('users.cooperative_id', $this->cooperativeId)
        ->with(['vet'])->get();
        return collect($vets);
    }

    public function headings(): array
    {
        return [
            'Name',
            'Phone', 
            'Email',
            'Gender',
            'Service Category'
        ];
    }

    public function map($vet): array
    {
        return [
            ucwords(strtolower($vet->first_name).' '.strtolower($vet->other_names) ),
            '+254'.' '.substr($vet->username,-9),
            $vet->email,
            $vet->vet->gender,
            $vet->vet->category
        ];   
    }
}
