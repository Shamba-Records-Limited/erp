<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RouteExport implements FromCollection, WithMapping, WithHeadings
{
    private $routes;

    public function __construct($routes)
    {
        $this->routes = $routes;
    }

    public function collection()
    {
        return collect($this->routes);
    }

    public function map($route): array
    {
        return [
            $route->name,
            Carbon::parse($route->created_at)->format('Y-m-d'),
        ];
    }

    public function headings(): array
    {
        return [
            'Route',
            'Created At',
        ];
    }
}
