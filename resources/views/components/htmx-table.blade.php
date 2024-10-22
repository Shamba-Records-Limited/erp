<div class="table-responsive" hx-on::load="paginate({{$page}}, {{$lastPage}})">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                @foreach($columns as $column)
                <th>{{$column['name']}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody id="tbody">
            @foreach($rows as $ind => $row)
            <tr>
                @foreach($columns as $column)
                <td>{!!$column['value']($row)!!}</td>
                @endforeach
                <!-- <td>
                    <div class="btn-group dropdown">
                        <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Actions
                        </button>
                        <div class="dropdown-menu">
                            <button class="text-info dropdown-item" onclick="printReceipt('{{-- $transaction->id --}}')">
                                <i class="fa fa-edit"></i> Print Receipt
                            </button>
                        </div>
                    </div>
                </td> -->
            </tr>
            @endforeach
        </tbody>

        <tfoot id="tfoot">
            <tr>
                @foreach($totalsColumn as $ind => $column)
                @if(strpos($column, 'span__') !== false)
                @php
                $colSpan = explode("__", $column)[1]
                @endphp
                <th colspan="{{$colSpan}}">@if($ind == 0)Totals: @endif</th>
                @else
                <th colspan="1">{{$column}}</th>
                @endif
                @endforeach
            </tr>
        </tfoot>

    </table>
</div>

<div class="d-flex justify-content-between">
    <div id="total-items">Items Count: {{number_format($totalItems)}}</div>
    <div>
        <input hx-get="{{$tableRoute}}" hx-trigger="change" hx-target="#tableContent" hx-include=".table-control" hx-swap="innerHTML" name="page" type="hidden" class="form-control table-control" id="page" value="{{$page}}" />
        <div id="items-pagination"></div>
    </div>
</div>