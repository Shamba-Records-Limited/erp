<div class="table-responsive" hx-on::load="paginate({{$page}}, {{$lastPage}})">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Transaction Number</th>
                <th>Subject</th>
                <th>Sender</th>
                <th>Recipient</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody id="tbody">
            @foreach($expenses as $transaction)
            <tr>
                <td>{{$transaction->transaction_number}}</td>
                <td>{{$transaction->subject}}</td>
                <td>{{$transaction->sender}}</td>
                <td>{{$transaction->recipient}}</td>
                <td>{{$transaction->formatted_amount}}</td>
                <td>{{$transaction->status}}</td>
                <td>{{$transaction->created_at}}</td>
            </tr>
            @endforeach
        </tbody>

        <tfoot id="tfoot">
            <tr>
                <th colspan="4">Total</th>
                <th colspan="1">KES. {{$expensesTotal}}</th>
            </tr>
        </tfoot>

    </table>
</div>

<div class="d-flex justify-content-between">
    <div id="total-items">Items Count: {{$totalItems}}</div>
    <div>
        <input hx-get="" hx-trigger="change" hx-target="#tableContent" hx-include=".table-control" hx-swap="innerHTML" name="page" type="hidden" class="form-control table-control" id="page" value="{{$page}}"/>
        <div id="items-pagination"></div>
    </div>
</div>