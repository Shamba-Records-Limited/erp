@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')
@php
if (empty($acc_type)) {
$acc_type = 'miller-admin';
}
@endphp
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div class="card-title">Account Payables</div>

            @php
            $exportRoute = $acc_type.".account-payables.export";
            @endphp
            <div class="d-flex">
                <button class="btn btn-primary" title="Add Filter" onclick="toggleFilterContainer()">
                    <span class="mdi mdi-filter"></span>
                    <span class="mdi mdi-plus">Add Filter</span>
                </button>
                @if($acc_type!=='admin') 
                @if($acc_type!=='farmer') 
                <div class="dropdown ml-2">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Make Payment
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" id="makePayment"
                            href="{{route($acc_type.'.wallet-management.view-make-payment')}}">Make Payment</a>
                    </div>
                </div>
                @endif
                @endif
                @if($acc_type!=='admin') 
                <a class="btn btn-primary ml-2" id="addOperationalExpense"
                    href="{{route($acc_type.'.wallet-management.operational-expenses.add')}}">Add Operational
                    Expense</a>
                    @endif
                <button class="btn btn-primary btn-fw btn-sm ml-1"
                    onclick="exportReport('{{route($exportRoute, 'xlsx')}}')"><span
                        class="mdi mdi-file-excel"></span>Export Excel
                </button>
                <button class="btn btn-primary btn-fw btn-sm ml-1"
                    onclick="exportReport('{{route($exportRoute, 'pdf')}}')"><span
                        class="mdi mdi-file-pdf"></span>Export Pdf
                </button>
            </div>



        </div>

        <div id="filter-display" class="d-flex filter-display align-items-start flex-wrap p-2">
            <input hx-get="{{route($acc_type.'.wallet-management.account-payables.table')}}" hx-trigger="change"
                hx-target="#tableContent" hx-include=".table-control" hx-swap="innerHTML" name="filter" type="hidden"
                class="table-control" id="filter" value="" />
            <div id="filter-container" class="border border-success rounded p-2 hidden">
                <select class="form-control" id="filter-select" onchange="filterSelectChanged(myFilterOptions)">
                    <option value="">-- Select Filter --</option>
                </select>
                <select class="form-control ml-2" id="filter-operator" onchange="filterOperatorChanged(myFilterOptions)"
                    disabled>
                    <option value="">-- Select Operator --</option>
                    <option value="is:">is:</option>
                </select>
                <input type="text" class="form-control ml-2" id="filter-value" disabled>
                <button class="btn btn-primary ml-2" id="filter-apply" disabled
                    onclick="filterApplyClicked()">Apply</button>
                <button class="btn btn-secondary ml-2" id="filter-clear" onclick="filterClearClicked()">Clear</button>
                <button class="btn btn-secondary ml-2" id="filter-close"
                    onclick="toggleFilterContainer()">&times;</button>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <div class="d-flex align-items-center">
                <label class="pt-1">Show:</label>
                <div class="ml-2">
                    <select hx-get="{{route($acc_type.'.wallet-management.account-payables.table')}}"
                        hx-trigger="change" hx-target="#tableContent" hx-include=".table-control:not(#page)"
                        hx-swap="innerHTML" class="form-control table-control" id="show-per-page" name="limit">
                        <option value="1">1</option>
                        <option value="5" selected>5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <label class="pt-1 ml-2">entries</label>
            </div>
            <div class="d-flex align-items-center">
                <label for="search" class="mr-2">Search: </label>
                <div>
                    <input hx-get="{{route($acc_type.'.wallet-management.account-payables.table')}}"
                        hx-trigger="keyup changed delay:500ms" hx-target="#tableContent"
                        hx-include=".table-control:not(#page)" hx-swap="innerHTML" name="search" type="search"
                        class="form-control table-control mb-2" placeholder="Search" aria-label="Search">
                </div>
            </div>
        </div>

        <div id="tableContent" hx-get="{{route($acc_type.'.wallet-management.account-payables.table')}}"
            hx-trigger="load" hx-swap="innerHTML">
            <div class="skeleton" style="height: 20px; width: 100%;"></div>
        </div>

    </div>
</div>
@endsection
@push('plugin-scripts')
@endpush

@push('custom-scripts')
<script>
let myFilterOptions = new Map([
    ["transaction_number", {}],
    ["subject", {}],
    ["sender", {}],
    ["recipient", {}],
    ["amount", {
        "isNumeric": true
    }],
    ["created_at", {
        "isNumeric": true
    }],
])

$(document).ready(function() {
    // init filter select
    filterInitOptions(myFilterOptions);
});


function printReceipt(transactionId) {
    $.ajax({
        url: `/transaction-receipts/${transactionId}/print`,
        method: 'GET',
        success: function(resp) {
            // alert(resp);
            printContent(resp);
        },
        error: function(errResp) {
            alert(errResp);
        }
    })
}

function paginate(currentPage, lastPage) {
    let paginationElem = createPaginationElem(currentPage, lastPage, function(pageNum) {
        // on page click
        if (pageNum < 1 || pageNum > lastPage) {
            return;
        }

        if (pageNum == currentPage) {
            return;
        }

        document.getElementById("page").value = pageNum;
        document.getElementById("page").dispatchEvent(new Event('change'));
    });

    document.getElementById("items-pagination").replaceWith(paginationElem);
}

function exportReport(url) {
    let tableControlElems = document.getElementsByClassName("table-control");

    let form = document.createElement("form");
    form.action = url;
    form.method = "GET";

    for (let elem of tableControlElems) {
        // duplicate elem
        let inp = document.createElement("input");
        inp.type = "hidden";
        inp.name = elem.name;
        inp.value = elem.value;
        form.appendChild(inp);
    }

    document.body.appendChild(form);
    form.submit();
}
</script>
@endpush