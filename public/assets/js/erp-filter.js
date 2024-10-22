"use strict";

function filterInitOptions(filterOptions) {
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
}

function filterInitOptionsV2(filterOptions) {
    let filterSelect = document.getElementById("filter-select");
    filterSelect.innerHTML = "";

    let option = document.createElement("option");
    option.value = "";
    option.text = "-- Select Filter --";
    filterSelect.appendChild(option);


    for (let optionDesc of filterOptions) {
        let splitOptionDesc = optionDesc.split("__");
        let key = splitOptionDesc[0];
        let isNumeric = splitOptionDesc.length > 1 ? splitOptionDesc[1] == "numeric" : false;

        let option = document.createElement("option");
        option.value = splitOptionDesc[0];
        option.text = splitOptionDesc[0];
        option.setAttribute("data-filter-key", splitOptionDesc[0]);
        option.setAttribute("data-is-numeric", isNumeric);
        filterSelect.appendChild(option);
    }
}

function filterSelectChanged(filterOptions) {
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
        filterOperatorChanged(filterOptions);

        document.getElementById("filter-value").disabled = false;
        document.getElementById("filter-apply").disabled = true;
    } else {
        document.getElementById("filter-operator").disabled = true;
        document.getElementById("filter-value").disabled = true;
        document.getElementById("filter-apply").disabled = true;
    }
};

function filterSelectChangedV2(filterOptions) {
    var filterKeyElem = document.getElementById("filter-select");
    var filterKey = filterKeyElem.value;

    var isNumeric = filterKeyElem.getAttribute("data-is-numeric");

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
        filterOperatorChangedV2(filterOptions);

        document.getElementById("filter-value").disabled = false;
        document.getElementById("filter-apply").disabled = true;
    } else {
        document.getElementById("filter-operator").disabled = true;
        document.getElementById("filter-value").disabled = true;
        document.getElementById("filter-apply").disabled = true;
    }
};

function filterOperatorChanged(filterOptions) {
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
            valueElem.onchange = function () {
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
            valueElem.onkeyup = function () {
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

function filterOperatorChangedV2(filterOptions) {
    // validates and creates filter value input or select
    var filterOperator = document.getElementById("filter-operator").value;

    var filterKeyElem = document.getElementById("filter-select");
    var isNumeric = filterKeyElem.getAttribute("data-is-numeric");

    var myOptions = [];
    var hasOptions = false;


    if (filterOperator) {
        let optionsOperators = ["=", "!="];
        if (optionsOperators.includes(filterOperator) && hasOptions) {
            let valueElem = document.createElement("select");
            valueElem.id = "filter-value";
            valueElem.className = "form-control ml-2";
            valueElem.onchange = function () {
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
            valueElem.onkeyup = function () {
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
    rawFilters = rawFilters.filter(function (f) {
        return f != filterToRemove;
    });
    filterElem.value = rawFilters.join(",");
    filterElem.dispatchEvent(new Event('change'));
}
