@extends('layout.master')

@push('plugin-styles')
<style>
    #filter-container.hidden {
        display: none;
        opacity: 0;
        transition: all 0.5s ease-in-out;
    }

    #filter-container.show {
        display: flex;
        opacity: 1;
    }

    .filter-display {
        gap: 5px;
    }
</style>
@endpush

@section('content')


<div class="card">
    <div class="card-body">
        <div class="card-title">Expenses</div>

        @php
        $exportRoute = $acc_type.".expenses.export";
        @endphp
        <div class="d-flex justify-content-end p-2">
            <button class="btn btn-primary" title="Add Filter" onclick="toggleFilterContainer()">
                <span class="mdi mdi-filter"></span>
                <span class="mdi mdi-plus"></span>
            </button>
            <button class="btn btn-primary btn-fw btn-sm ml-1" onclick="exportReport('{{route($exportRoute, 'xlsx')}}')"><span class="mdi mdi-file-excel"></span>Export Excel
            </button>
            <button class="btn btn-primary btn-fw btn-sm ml-1" onclick="exportReport('{{route($exportRoute, 'pdf')}}')"><span class="mdi mdi-file-pdf"></span>Export Pdf
            </button>
        </div>

        <div id="filter-display" class="d-flex filter-display align-items-start flex-wrap p-2">
            <input hx-get="{{route('cooperative-admin.wallet-management.expenses.table')}}" hx-trigger="change" hx-target="#tableContent" hx-include=".table-control" hx-swap="innerHTML" name="filter" type="hidden" class="table-control" id="filter" value="" />
            <div id="filter-container" class="border border-success rounded p-2 hidden">
                <select class="form-control" id="filter-select" onchange="filterSelectChanged()">
                    <option value="">-- Select Filter --</option>
                </select>
                <select class="form-control ml-2" id="filter-operator" onchange="filterOperatorChanged()" disabled>
                    <option value="">-- Select Operator --</option>
                    <option value="is:">is:</option>
                </select>
                <input type="text" class="form-control ml-2" id="filter-value" disabled>
                <button class="btn btn-primary ml-2" id="filter-apply" disabled onclick="filterApplyClicked()">Apply</button>
                <button class="btn btn-secondary ml-2" id="filter-clear" onclick="filterClearClicked()">Clear</button>
                <button class="btn btn-secondary ml-2" id="filter-close" onclick="toggleFilterContainer()">&times;</button>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <div class="d-flex align-items-center">
                <label class="pt-1">Show:</label>
                <div class="ml-2">
                    <select hx-get="{{route('cooperative-admin.wallet-management.expenses.table')}}" hx-trigger="change" hx-target="#tableContent" hx-include=".table-control" hx-swap="innerHTML" class="form-control table-control" id="show-per-page" name="limit" onchange="showPerPageChanged()">
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
                    <input hx-get="{{route('cooperative-admin.wallet-management.expenses.table')}}" hx-trigger="keyup changed delay:500ms" hx-target="#tableContent" hx-include=".table-control" hx-swap="innerHTML" name="search" type="search" class="form-control table-control mb-2" placeholder="Search" aria-label="Search">
                </div>
            </div>
        </div>

        <div id="tableContent" hx-get="{{route('cooperative-admin.wallet-management.expenses.table')}}" hx-trigger="load" hx-swap="innerHTML">
            <div class="skeleton" style="height: 20px; width: 100%;"></div>
        </div>


    </div>
</div>
@endsection
@push('plugin-scripts')
@endpush

@push('custom-scripts')
<script>
    // let data = @json($expenses);
    let data = [];
    // let filter = @json($filter);

    let filterOptions = new Map([
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
        let filterSelect = document.getElementById("filter-select");
        filterSelect.innerHTML = "";

        let option = document.createElement("option");
        option.value = "";
        option.text = "-- Select Filter --";
        filterSelect.appendChild(option);

        filterOptions.forEach((value, key) => {
            let option = document.createElement("option");
            option.value = key;
            option.text = key;
            filterSelect.appendChild(option);
        })
    });

    function filterSelectChanged() {
        var filterKeyElem = document.getElementById("filter-select");
        var filterKey = filterKeyElem.value;
        var filterOption = filterOptions.get(filterKey);

        var isNumeric = filterOption["isNumeric"] || false;
        var myOptions = filterOption["options"] || [];
        var hasOptions = myOptions.length > 0;

        let filterOperatorElem = document.getElementById("filter-operator");
        filterOperatorElem.innerHTML = "";
        let option = document.createElement("option");
        option.value = "";
        option.text = "-- Select Operator --";
        filterOperatorElem.appendChild(option);

        if (filterKey) {
            let myOperators = ["has", "doesn't have", "=", "!=", ">", "<", ">=", "<="];

            for (let i = 0; i < myOperators.length; i++) {
                if (isNumeric && i < 2) {
                    continue;
                }

                let option = document.createElement("option");
                option.value = myOperators[i];
                option.text = myOperators[i];
                if (!isNumeric && i == 0) {
                    option.selected = true;
                }
                if (isNumeric && i == 2) {
                    option.selected = true;
                }
                filterOperatorElem.appendChild(option);
            }

            document.getElementById("filter-operator").disabled = false;
            filterOperatorChanged();

            document.getElementById("filter-value").disabled = false;
            document.getElementById("filter-apply").disabled = true;
        } else {
            document.getElementById("filter-operator").disabled = true;
            document.getElementById("filter-value").disabled = true;
            document.getElementById("filter-apply").disabled = true;
        }
    };

    /**
     * filterOperatorChanged
     * validates and creates filter value input or select
     * based on the selected filter key
     *  
     */
    function filterOperatorChanged() {
        // validates and creates filter value input or select
        var filterOperator = document.getElementById("filter-operator").value;

        var filterKey = document.getElementById("filter-select").value;
        var filterOption = filterOptions.get(filterKey);

        var myOptions = filterOption["options"] || [];
        var hasOptions = myOptions.length > 0;


        var isNumeric = filterOption["isNumeric"] || false;

        if (filterOperator) {
            let optionsOperators = ["=", "!="];
            if (optionsOperators.includes(filterOperator) && hasOptions) {
                let valueElem = document.createElement("select");
                valueElem.id = "filter-value";
                valueElem.className = "form-control ml-2";
                valueElem.onchange = function() {
                    filterValueChanged();
                }


                let defaultOption = document.createElement("option");
                defaultOption.value = "";
                defaultOption.text = "-- Select Value --";
                valueElem.appendChild(defaultOption);

                for (let i = 0; i < myOptions.length; i++) {
                    let option = document.createElement("option");
                    option.value = myOptions[i];
                    option.text = myOptions[i];
                    valueElem.appendChild(option);
                }

                document.getElementById("filter-value").replaceWith(valueElem);
            } else {
                let valueElem = document.createElement("input");
                valueElem.id = "filter-value";
                valueElem.className = "form-control ml-2";
                valueElem.onkeyup = function() {
                    filterValueKeyUp();
                }

                document.getElementById("filter-value").replaceWith(valueElem);
            }

            document.getElementById("filter-apply").disabled = true;
        } else {
            document.getElementById("filter-value").disabled = true;
            document.getElementById("filter-apply").disabled = true;
        }
    };

    function filterValueKeyUp() {
        let filterValue = document.getElementById("filter-value").value;

        if (filterValue) {
            document.getElementById("filter-apply").disabled = false;
        } else {
            document.getElementById("filter-apply").disabled = true;
        }
    }

    function filterValueChanged() {
        let filterValue = document.getElementById("filter-value").value;

        if (filterValue) {
            document.getElementById("filter-apply").disabled = false;
        } else {
            document.getElementById("filter-apply").disabled = true;
        }
    }

    function filterClearClicked() {
        document.getElementById("filter-select").value = "";
        document.getElementById("filter-operator").value = "";
        document.getElementById("filter-value").value = "";

        document.getElementById("filter-operator").disabled = true;
        document.getElementById("filter-value").disabled = true;
    }

    function toggleFilterContainer() {
        let filterContainer = document.getElementById("filter-container");
        if (filterContainer.classList.contains("hidden")) {
            filterContainer.classList.remove("hidden");
            filterContainer.classList.add("show");
        } else {
            filterContainer.classList.remove("show");
            filterContainer.classList.add("hidden");
        }
    }

    function filterApplyClicked() {
        let filterSelect = document.getElementById("filter-select");
        let filterOperator = document.getElementById("filter-operator");
        let filterValue = document.getElementById("filter-value");

        let myFilter = `${filterSelect.value}__${filterOperator.value}__${filterValue.value}`;

        // assign object key to filterSelect value
        let filterElem = document.getElementById("filter");
        let rawFilters = filterElem.value.split(",");

        if (rawFilters.includes(myFilter)) {
            return;
        }

        rawFilters.push(myFilter);
        rawFilters = rawFilters.sort();

        filterElem.value = rawFilters.join(",");
        filterElem.dispatchEvent(new Event('change'));

        let tags = document.getElementsByClassName("filter-tag");
        for (let tag of tags) {
            tag.remove();
        }

        for (let f of rawFilters) {
            if (!f) {
                continue;
            }

            let splitFilter = f.split("__");

            let filterTag = document.createElement("div");
            filterTag.className = "d-flex border rounded p-1 filter-tag";

            filterTag.innerHTML = `
                <div class="font-weight-bold key-text">${splitFilter[0]}</div>
                <div>: <span class="operand-text">${splitFilter[1]}</span> <span class="value-text">${splitFilter[2]}</span></div>
                <button class="btn btn-sm btn-light" onclick="removeFilterClicked('${f}')">&times;</button>
            `;

            document.getElementById("filter-display").appendChild(filterTag);
        }

        filterClearClicked();
        toggleFilterContainer();
    }

    function removeFilterClicked(filterToRemove) {
        let filterTags = document.getElementsByClassName("filter-tag");
        for (let i = 0; i < filterTags.length; i++) {
            let keyTextElem = filterTags[i].getElementsByClassName("key-text")[0];
            let operandTextElem = filterTags[i].getElementsByClassName("operand-text")[0];
            let valueTextElem = filterTags[i].getElementsByClassName("value-text")[0];

            let myFilter = `${keyTextElem.innerText}__${operandTextElem.innerText}__${valueTextElem.innerText}`;
            if (myFilter == filterToRemove) {
                filterTags[i].remove();
                break;
            }
        }

        // remove filter from filterElem
        let filterElem = document.getElementById("filter");
        let rawFilters = filterElem.value.split(",");
        rawFilters = rawFilters.filter(function(f) {
            return f != filterToRemove;
        });
        filterElem.value = rawFilters.join(",");
        filterElem.dispatchEvent(new Event('change'));
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