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
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="tbody">
            @foreach($receivables as $transaction)
            <tr>
                <td>{{$transaction->transaction_number}}</td>
                <td>{{$transaction->subject}}</td>
                <td>{{$transaction->sender}}</td>
                <td>{{$transaction->recipient}}</td>
                <td>{{$transaction->formatted_amount}}</td>
                <td>{{$transaction->created_at}}</td>
                @php
                $statusCls = 'text-warning';
                if($transaction->status == 'COMPLETE'){
                $statusCls = 'text-success';
                } elseif($transaction->status == 'PENDING') {
                $statusCls = 'text-warning';
                } else {
                $statusCls = 'text-danger'; // Example for other statuses
                }
                @endphp
                <td>
                    <div class="{{ $statusCls }}">
                        {{$transaction->status}}
                    </div>
                </td>
                <td>
                    <div class="btn-group dropdown">
                        <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            Actions
                        </button>
                        <div class="dropdown-menu">
                            <a class="text-primary dropdown-item"
                                href="{{route($acc_type.'.transactions.detail', $transaction->id )}}">
                                <i class="fa fa-edit"></i> View Details
                            </a>
                            @if($transaction->status == 'PENDING')
                            <a class="text-success dropdown-item"
                                href="/{{$acc_type}}/wallet-management/transactions/{{$transaction->id}}/complete?to='{{$acc_type}}.wallet-management.account-payables'">
                                <i class="fa fa-edit"></i> Complete
                            </a>
                            @endif
                            @if($transaction->status == 'COMPLETE')
                            <button class="text-info dropdown-item" onclick="printReceipt('{{$transaction->id}}')">
                                <i class="fa fa-edit"></i> Print Receipt
                            </button>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot id="tfoot">
            <tr>
                <th colspan="4">Total</th>
                <th colspan="1">KSH. {{number_format($receivablesTotal)}}</th>
            </tr>
        </tfoot>

    </table>
</div>

<div class="d-flex justify-content-between">
    <div id="total-items">Items Count: {{number_format($totalItems)}}</div>
    <div>
        <input hx-get="{{route($acc_type.'.wallet-management.account-receivables.table')}}" hx-trigger="change"
            hx-target="#tableContent" hx-include=".table-control" hx-swap="innerHTML" name="page" type="hidden"
            class="form-control table-control" id="page" value="{{$page}}" />
        <div id="items-pagination"></div>
    </div>
</div>