<?php

namespace App\Exports;

use App\User;
use App\VetBooking;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VetBookingsExport implements FromCollection,WithMapping,WithHeadings
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        $bookings = VetBooking::bookings($this->user);
        return collect($bookings);
    }
    public function map($booking): array
    {
        return [
            ucwords(strtolower($booking->farmer->first_name) . ' ' . strtolower($booking->farmer->other_names)),
            $booking->event_name,
            ucwords(strtolower($booking->vet->first_name) . ' ' . strtolower($booking->vet->other_names)),
            $booking->booking_type,
            $booking->service ? $booking->service->name : '-',
            number_format($booking->charges),
            $booking->status
        ];
    }

    public function headings(): array
    {
        return [
            'Farmer',
            'Purpose',
            'Vet',
            'Booking Type',
            'Service',
            'Vet Charges',
            'Status',
        ];
    }
}
