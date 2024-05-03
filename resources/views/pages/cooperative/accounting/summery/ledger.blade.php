<div class="table-responsive">
    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">{{ 'Date' }}</th>
            <th scope="col" class="text-left">{{ 'Credit' }}</th>
            <th scope="col" class="text-left">{{ 'Debit' }}</th>
            <th scope="col" class="text-left">{{ 'Particulars' }}</th>
        </tr>
        </thead>
        <tbody>

        @php
            $currency = auth()->user()->cooperative->currency;
            $total_debits = 0;
            $total_credits = 0;
        @endphp
        @foreach($records as $r)
            @php
                $total_debits += $r->debit;
                $total_credits += $r->credit;
            @endphp
            <tr>
                <td class="text-left">
                    {{ $r->date }}
                </td>
                <td class="text-left">
                    {{ $r->credit !== null ? $currency.' '.number_format($r->credit, 2, '.',',') : null }}
                </td>
                <td class="text-left">
                    {{ $r->debit !== null ? $currency.' '.number_format($r->debit, 2, '.',',') : null }}
                </td>
                <td class="text-left">
                    {{ $r->particulars }}
                </td>
            </tr>
        @endforeach
        {{-- Summary --}}
        <tr>
            <th class="text-left mt-3" colspan="">{{ 'Totals' }}</th>
            <th class="text-left">
                {{ $currency.' '.number_format($total_credits, 2, '.',',') }}
            </th>
            <th class="text-left" colspan="2">
                {{ $currency.' '.number_format($total_debits, 2, '.',',') }}
            </th>
        </tr>
        <tr>
            <th colspan="4"></th>
        </tr>

        </tbody>
    </table>
</div>
