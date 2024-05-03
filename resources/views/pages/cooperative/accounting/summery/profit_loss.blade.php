<div class="table-responsive">
    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">{{ 'Date' }}</th>
            <th scope="col" class="text-left">{{ 'Income' }}</th>
            <th scope="col" class="text-left">{{ 'Expense' }}</th>
            <th scope="col" class="text-left">{{ 'Particulars' }}</th>
        </tr>
        </thead>
        <tbody>

        @php
            $currency = auth()->user()->cooperative->currency;
            $total_income = 0;
            $total_expense = 0;
        @endphp
        @foreach($income_expenses as $r)
            @php
                $total_income += $r->income;
                $total_expense += $r->expense;
            @endphp
            <tr>
                <td class="text-left">
                    {{ $r->date }}
                </td>
                <td class="text-left">
                    {{ $r->income !== null ? $currency.' '.number_format($r->income, 2, '.',',') : null }}
                </td>
                <td class="text-left">
                    {{ $r->expense !== null ? $currency.' '.number_format($r->expense, 2, '.',',') : null }}
                </td>
                <td class="text-left">
                    {{ $r->particulars }}
                </td>
            </tr>
        @endforeach
        {{-- Summary --}}
        <tr>
            <th class="text-left mt-3">{{ 'Balance B/F' }}</th>
            <th class="text-left" colspan="3">
                {{ $currency.' '.number_format($balance_bf, 2, '.',',') }}
            </th>
        </tr>
        <tr>
            <th class="text-left mt-3">{{ 'Totals' }}</th>
            <th class="text-left">
                {{ $currency.' '.number_format($total_income + $balance_bf, 2, '.',',') }}
            </th>
            <th class="text-left" colspan="2">
                {{ $currency.' '.number_format($total_expense, 2, '.',',') }}
            </th>
        </tr>
        <tr>
            <th colspan="4"></th>
        </tr>

        </tbody>
    </table>
</div>
