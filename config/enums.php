<?php

return [
    "genders" => [
        [
            "M",
            "F",
            "X"
        ]
    ],

    "payment_platforms" => [
        [
            "mobile",
            "cash",
            "bank",
            "crypto"
        ]
    ],
    "titles" => [
        [
            "Mr.",
            "Mrs.",
            "Miss.",
            "Dr.",
            "Proff.",
            "Pstr.",
            "Rev.",
        ]
    ],
    "sale_types" => [
        [
            "sale",
            "quotation"
        ]
    ],
    "transaction_types" => [
        [
            "debit",
            "credit"
        ]
    ],
    "property_status" => [
        [
            "available",
            "sold"
        ]
    ],
    "financial_period_types" => [
        [
            "monthly",
            "quarterly",
            "annually"
        ]
    ],
    "accounting_ledgers_type" => [
        [
            "current",
            "long term",
        ]
    ],
    'crop_calendar_period_measure' => [
        [
            'days', 'weeks', 'months', 'years'
        ]
    ],
    'farmer_crop_status' => [
        [
            'not started', 'in progress', 'halted', 'completed'
        ]
    ],
    'volume_indicator' => [
        ['season', 'acre', 'livestock/poultry', 'tree']
    ],
    'disease_status' => [
        ['Mild', 'Fatal', 'Critical', 'Recovered']
    ],
    'vet_service_types' => [
        ['Vet', 'Extension']
    ],
    'booking_status' => [
        ['Pending', 'Checked', 'Resolved']
    ],
    'system_permissions' => [
        'view' => 'can_view',
        'create' => 'can_create',
        'edit' => 'can_edit',
        'delete' => 'can_delete',
        'download' => 'can_download_report'
    ],
    'system_modules' => [
        'Farmer CRM' => [
            'dashboard' => 'Dashboard',
            'routes' => 'Routes',
            'farmers' => 'Farmers'
        ],
        'Product Management' => [
            'dashboard' => 'Dashboard',
            'units' => 'Units',
            'categories' => 'Categories',
            'products' => 'Products',
            'suppliers' => 'Suppliers'
        ],
        'Logistics' => [
            'dashboard' => 'Dashboard',
            'vehicle_types' => 'Vehicle Types',
            'vehicles' => 'Vehicles',
            'transport_providers' => 'Transport Providers',
            'weighbridge' => 'Weighbridge Management',
            'trip_management' => 'Trip Management'
        ],
        'Collections' => [
            'quality_std' => 'Quality Standards',
            'dashboard' => 'Dashboard',
            'collect' => 'Collect',
            'submitted_collection' => 'Submitted Collection',
            'bulk_payment' => 'Bulk Payments',
        ],
        'Bank Management' => [
            'banks' => 'Banks',
            'branches' => 'Branches',
        ],
        'Farm Management' => [
            'dashboard' => 'Dashboard',
            'breed_registration' => 'Breed Registration',
            'livestock_poultry' => 'Livestock/Poultry',
            'farm_units' => 'Farm Units',
            'crop_details' => 'Crop Details',
            'calendar_stages' => 'Farm Calendar Stages',
            'farmer_calendar' => 'Farmer Calendar',
            'yield_config' => 'Yield Configuration',
            'yields' => 'Farmer Yields',
        ],
        'Disease Management' => [
            'dashboard' => 'Dashboard',
            'categories' => 'Categories',
            'diseases' => 'Diseases',
            'disease_cases' => 'Disease Cases',
        ],
        'Vet & Extension Services' => [
            'services' => 'Services',
            'items' => 'Items',
            'vets' => 'Vets',
            'bookings' => 'Bookings',
        ],
        'HR Management' => [
            'dashboard' => 'Dashboard',
            'branches' => 'Branches',
            'departments' => 'Departments',
            'job_type' => 'Job Type',
            'job_positions' => 'Job Positions',
            'employees' => 'Employees',
            'payroll' => 'Payroll',
            'department_payroll' => 'Department Payroll',
            'files' => 'Files',
            'leave' => 'Leave',
            'recruitment' => 'Recruitment',
            'reports' => 'Reports',
        ],

        'User Management' => [
            'roles' => 'Roles',
            'role_management' => 'Role Management',
            'module_management' => 'Module Management',
            'permissions' => 'User Permission',
            'role_permissions' => 'Role Permission',
            'activity_log' => 'Activity Log',
        ],
        'Manufacturing' => [
            'reports' => 'Reports',
            'final_products' => 'Final Products',
            'raw_materials' => 'Raw Materials',
            'production' => 'Production',
            'expired_stock' => 'Expired Stock',
        ],

        'Procurement' => [
            'suppliers' => 'Suppliers',
            'purchase_orders' => 'Purchase Orders',
            'store' => 'Store',
        ],
        'Customer Management' => [
            'crm' => 'Customer Management',
            'customers' => 'Customers'
        ],
        'Sales' => [
            'invoice' => 'Invoice',
            'void_invoices' => 'Void Invoices',
            'quotation' => 'Quotation',
            'reports' => 'Reports',
            'returned_items' => 'Returned Items'
        ],
        'Accounting' => [
            'wallet' => 'Wallet',
            'charts_of_account' => 'Charts of Account',
            'accounting_rules' => 'Accounting Rules',
            'journal_entries' => 'Journal Entries',
            'reports' => 'Reports',
            'asset' => 'Asset',
            'budget' => 'Budget',
        ],
        'Financial Products' => [
            'dashboard' => 'Dashboard',
            'loan_products' => 'Loan Products',
            'saving_types' => 'Saving Types',
            'loan_application' => 'Loan Application',
            'group_loans' => 'Group Loans',
            'current_savings' => 'Current Savings',
            'loan_defaulters' => 'Loan Defaulters',
            'loan_repayments' => 'Loan Repayments',
            'interest' => 'Interest',
            'group_loan_type' => 'Group Loan Types',
            'group_loan_setting' => 'Group Loan Settings',
            'group_loan_repayments' => 'Group Loan Repayments',
            'limit_rate_setting' => 'Credit/Loan Score Setting',
        ],
        'Insurance Product' => [
            'product_benefits' => 'Insurance Benefits',
            'product_premiums' => 'Insurance Products',
            'premium_adjustments' => 'Premium Adjustments',
            'valuation' => 'Valuation',
            'insurance_subscription' => 'Insurance Subscription',
            'product_limit' => 'Set Insurance limit',
            'claims' => 'Claims Management',
            'reports' => 'Reports',
        ]
    ],
    'employee_configs' => [
        'gender' => [
            'male' => 'Male',
            'female' => 'Female',
            'other' => 'Female',
        ],
        'marital_status' => [
            'married' => 'Married',
            'divorced' => 'Divorced',
            'engaged' => 'Engaged',
            'single' => 'Single'
        ]
    ],
    "loan_payment_options" => [
        [
            "1" => "Wallet",
            "2" => "M-PESA",
        ]
    ],
    "farm_tools" => [
        [
            "0" => "None",
            "1" => "Vehicle",
            "2" => "Machinery",
            "3" => "Others"
        ]
    ], "loan_status" => [
        [
            "0" => "Rejected",
            "1" => "Approved",
            "2" => "Repaid",
            "3" => "Partial Payment",
            "4" => "Bought Off",
            "5" => "Pending",
        ]
    ],
    "customer_types" => [
        [
            "1" => "Individual",
            "2" => "Company",
        ]
    ],
    "supply_types" => [
        [
            "1" => "Internal",
            "2" => "Supplier",
        ]
    ],
    "supply_payment_status" => [
        [
            "1" => "Paid",
            "2" => "Pending",
            "3" => "Partial",
        ]
    ],
    "will_expire" => [
        [
            "1" => "Yes",
            "2" => "No",
        ]
    ],
    "delivery_status" => [
        [
            "1" => "Delivered",
            "2" => "Pending",
        ]

    ],
    "expiry_status" => [
        [
            "1" => "Valid Status",
            "2" => "Expired",
        ]
    ],
    "farmer_customer_types" => [
        'weekly' => 'Weekly', 'fortnight' => 'Fortnight', 'monthly' => 'Monthly'
    ],
    "collection_submission_statuses" => [
        '1' => 'Pending', '2' => 'Approved', '3' => 'Rejected'
    ],
    "invoice_status" => [
        '0' => 'Unpaid',
        '1' => 'Paid',
        '2' => 'Partial Payment',
        '3' => 'Returns Recorded'
    ],

    "hr_reports" => [
        "p9" => "P9",
        "p10" => "P10",
        "net pay" => "Net Pay",
        "gross pay" => "Gross Pay",
        "nhif" => "NHIF",
        "nssf" => "NSSF",
        "housing fund" => "Housing Fund",
        "deductions" => "Deductions",
        "allowances" => "Allowances",
    ],
    "deduction_types" => [
        "0" => "All",
        "1" => "Statutory",
        "2" => "Non Statutory"
    ],
    "deduction_report_period" => [
        "1" => "Annual",
        "2" => "Monthly"
    ],
    "Months" => [
        "1" => "January",
        "2" => "February",
        "3" => "March",
        "4" => "April",
        "5" => "May",
        "6" => "June",
        "7" => "July",
        "8" => "August",
        "9" => "September",
        "10" => "October",
        "11" => "November",
        "12" => "December",
    ],
    "hr_deduction_types" => [
        "benefit" => "Benefit",
        "deduction" => "Deduction",
        "insurance" => "Insurance",
        "pension" => "Pension",
    ],
    'bulk_payment_modes' => [
        '1' => 'Internal Transfer',
        '2' => 'Offline Payment'
    ], 'bulk_payment_status' => [
        '1' => 'Completed',
        '2' => 'Pending'
    ],
    'collection_time' => [
        '1' => 'Morning',
        '2' => 'Afternoon',
        '3' => 'Evening'
    ],
    'employment_status' => [
        '1' => 'Active',
        '2' => 'Suspended With Pay',
        '3' => 'Suspension Without Pay',
        '4' => 'Deactivated',
    ],
    'user_status' => [
        '0' => 'Deactivated',
        '1' => 'Active',
        '2' => 'Suspended With Pay',
        '3' => 'Suspension Without Pay'
    ],
    'disciplinary_types' => [
        '2' => 'Suspension With Pay',
        '3' => 'Suspension Without Pay',
        '4' => 'Termination',
    ],
    'appraisal_types' => [
        '1' => 'Promotion',
        '2' => 'Salary Raise',
        '3' => 'Salary Cut',
        '4' => 'Demotion',
        '5' => 'Performance Improvement Plan (PIP)',
        '6' => 'Initial Employment',
    ],
    'advance_deduction_status' => [
        '1' => 'Active',
        '2' => 'Closed',
    ],
    'advance_deduction_types' => [
        '1' => 'Mortgage',
        '2' => 'Salary Advance',
        '3' => 'Loan',
    ],
    'payroll_deduction_base_value' => [
        '0' => 'Gross Pay Reducing',
        '1' => 'Gross Pay Fixed'
    ],
    'units' => [
        'KG' => [
            'name' => 'Kilograms'
        ],
        'G' => [
            'name' => 'Grams',
        ],
        'L' => [
            'name' => 'Litres',
        ],
        'ML' => [
            'name' => 'Millilitres',
        ],
        'M' => [
            'name' => 'Meters',
        ],
        'CM' => [
            'name' => 'Centimeters',
        ],
        'IN' => [
            'name' => 'Inches',
        ],
    ]
];
