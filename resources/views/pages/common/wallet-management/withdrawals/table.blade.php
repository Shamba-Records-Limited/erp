<div class="table-responsive" hx-on::load="paginate({{$page}}, {{$lastPage}})">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Transaction Number</th>
                <th>Amount</th>
                <th>Purpose</th>
                <th>Status</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody id="tbody">
            @foreach($withdrawals as $transaction)
            <tr>
                <td>{{$transaction->transaction_number}}</td>
                <td>{{$transaction->formatted_amount}}</td>
                <td>{{$transaction->purpose}}</td>
                <td>{{$transaction->status}}</td>
                <td>{{$transaction->created_at}}</td>
            </tr>
            @endforeach
        </tbody>

        <tfoot id="tfoot">
            <tr>
                <th colspan="4">Total</th>
                <th colspan="1">KSH. {{number_format($withdrawalsTotal)}}</th>
            </tr>
        </tfoot>

    </table>
</div>

<div class="d-flex justify-content-between">
    <div id="total-items">Items Count: {{number_format($totalItems)}}</div>
    <div>
        <input hx-get="{{route($acc_type.'.wallet-management.withdrawals.table')}}" hx-trigger="change" hx-target="#tableContent" hx-include=".table-control" hx-swap="innerHTML" name="page" type="hidden" class="form-control table-control" id="page" value="{{$page}}" />
        <div id="items-pagination"></div>
    </div>
</div>