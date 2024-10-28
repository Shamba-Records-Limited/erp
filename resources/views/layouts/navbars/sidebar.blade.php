@php
try{
$miller = $user->miller_admin->miller;
} catch (\Throwable $th) {
$miller = null;
}

try{
$cooperative = $user->cooperative;
} catch (\Throwable $th) {
$cooperative = null;
}

@endphp
<nav class="navbar navbar-vertical fixed-left navbar-expand-md" id="sidenav-main">
    <div class="nav-profile-wrapper">
        <ul class="nav">
            <li class="nav-item nav-profile not-navigation-link">
                <div class="nav-link">
                    <div class="user-wrapper p-4">
                        @php $user = Auth::user(); @endphp
                        <div class="coop-profile-image">
                            <img class="coop-profile-image" src="{{ url('assets/images/avatar.jpg') }}"
                                alt="profile image">
                        </div>
                        <div class="text-wrapper">
                            <p class="profile-name">
                                @if($user)
                                {{ ucwords(strtolower($user->first_name)) }}
                                {{ ucwords(strtolower($user->other_names)) }}
                                @endif
                            </p>
                            <div class="dropdown" data-display="static">
                                <a href="#" class="nav-link d-flex user-switch-dropdown-toggler"
                                    id="UsersettingsDropdown" data-toggle="dropdown" aria-expanded="false">
                                    @if($user)
                                    @php
                                    $roles = $user->getRoleNames();
                                    @endphp
                                    <!-- Role name section -->
                                    <small class="designation text-muted semi-bold">
                                        @foreach($roles as $role)
                                        {{ ucwords(strtolower($role)) }}
                                        @endforeach
                                    </small>
                                    @endif
                                </a>
                                <!-- Status Indicator -->
                                <div class="status-indicator ml-3">
                                    <span class="bg-success dot"></span>
                                    <small><span class=" text-success semi-bold">Online</span></small>
                                </div>
                                <small class="designation text-muted">
                                    @if (!is_null($cooperative))
                                    {{ ucwords(strtolower($cooperative->name)) }}
                                    @elseif (!is_null($miller))
                                    {{ $miller->name }}
                                    @endif
                                </small>
                                <div class="dropdown-menu" aria-labelledby="UsersettingsDropdown">
                                    <a class="dropdown-item p-0">
                                        <div class="d-flex border-bottom">
                                            <div class="py-3 px-4 d-flex align-items-center justify-content-center">
                                                <i class="mdi mdi-bookmark-plus-outline mr-0 text-gray"></i>
                                            </div>
                                            <div
                                                class="py-3 px-4 d-flex align-items-center justify-content-center border-left border-right">
                                                <i class="mdi mdi-account-outline mr-0 text-gray"></i>
                                            </div>
                                            <div class="py-3 px-4 d-flex align-items-center justify-content-center">
                                                <i class="mdi mdi-alarm-check mr-0 text-gray"></i>
                                            </div>
                                        </div>
                                    </a>
                                    <a class="dropdown-item mt-2"> Manage Accounts </a>
                                    <a class="dropdown-item"> Change Password </a>
                                    <a class="dropdown-item"> Check Inbox </a>
                                    <a class="dropdown-item"> Sign Out </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <div class="nav-items-wrapper">
        <ul class="nav flex-column">
            <!-- Added flex-column to enforce vertical stacking -->
            @if($user && $user->hasRole('admin'))
            <li class="nav-item {{ active_class(['admin/dashboard']) }} mb-3">
                <!-- Added margin-bottom for spacing -->
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="ni ni-tv-2 text-custom-green"></i> {{ __('Dashboard') }}
                </a>
            </li>
            <li class="nav-item {{ active_class(['admin/cooperative/setup*']) }} mb-3">
                <a class="nav-link" href="{{ route('cooperative') }}">
                    <i class="ni ni-bullet-list-67 text-custom-green"></i> {{ __('Cooperatives') }}
                </a>
            </li>
            <li class="nav-item {{ active_class(['admin/branches']) }} mb-3">
                <a class="nav-link" href="{{ route('branches.show') }}">
                    <i class="ni ni-folder-17 text-custom-green"></i> {{ __('Cooperatives Branches') }}
                </a>
            </li>
            <li class="nav-item {{ active_class(['admin/millers*']) }} mb-3">
                <a class="nav-link" href="{{ route('admin.millers.show') }}">
                    <i class="ni ni-basket text-custom-green"></i> {{ __('Millers') }}
                </a>
            </li>
            <li class="nav-item {{ active_class(['admin/miller-branches*']) }} mb-3">
                <a class="nav-link" href="{{ route('admin.miller-branches.show') }}">
                    <i class="ni ni-shop text-custom-green"></i> {{ __('Miller Branches') }}
                </a>
            </li>
            <li class="nav-item {{ active_class(['admin/users']) }} mb-3">
                <a class="nav-link" href="{{ route('admin.users.show') }}">
                    <i class="ni ni-single-02 text-custom-green"></i> {{ __('User') }}
                </a>
            </li>
            <li class="nav-item {{ active_class(['admin/county-govt-officials']) }} mb-3">
                <a class="nav-link" href="{{ route('admin.county-govt-officials.show') }}">
                    <i class="ni ni-circle-08 text-custom-green"></i> {{ __('County Govt Officials') }}
                </a>
            </li>
            <li class="nav-item {{ active_class(['admin/farmers*']) }} mb-3">
                <a class="nav-link" href="{{ route('admin.farmers.show') }}">
                    <i class="ni ni-user-run text-custom-green"></i> {{ __('Farmers') }}
                </a>
            </li>
            <li class="nav-item {{ active_class(['admin/collections*']) }} mb-3">
                <a class="nav-link" href="{{ route('admin.collections.show') }}">
                    <i class="ni ni-credit-card text-custom-green"></i> {{ __('Collections') }}
                </a>
            </li>
            <li class="nav-item {{ active_class(['admin/roles*']) }} mb-3">
                <a class="nav-link" href="{{ route('admin.roles.show') }}">
                    <i class="ni ni-key-25 text-custom-green"></i> {{ __('Roles') }}
                </a>
            </li>

            <!-- Product Management Dropdown -->
            <li class="nav-item mb-3">
                <!-- Added margin-bottom for spacing -->
                <a class="nav-link" href="#products-management" data-toggle="collapse" role="button"
                    aria-expanded="{!! is_active_route(['admin/products/*']) !!}" aria-controls="products-management">
                    <i class="ni ni-box-2 text-custom-green"></i>
                    <span class="nav-link-text" style="color: #f4645f;">{{ __('Product Management') }}</span>
                </a>
                <div class="collapse {{ show_class(['admin/products/*']) }}" id="products-management">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.products.dash') }}">
                                {{ __('Dashboard') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.products.show') }}">
                                {{ __('Products') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.products.units') }}">
                                {{ __('Units') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.products.categories') }}">
                                {{ __('Categories') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.products.grades') }}">
                                {{ __('Grading') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item {{ active_class(['admin/support*']) }} mb-3">
                <a class="nav-link" href="{{ route('admin.support.show') }}">
                    <i class="ni ni-support-16 text-custom-green"></i> {{ __('Support') }}
                </a>
            </li>

            <!-- Chat now placed below Support -->
            <li class="nav-item {{ active_class(['chat*']) }} mb-3">
                <a class="nav-link" href="{{ route('chat.index') }}">
                    <i class="ni ni-chat-round text-custom-green"></i> {{ __('Chat') }}
                </a>
            </li>
            @endif
        </ul>
    </div>





    @if($user && $user->hasRole('county govt official'))
    <li class="nav-item {{ active_class(['county-govt/dashboard']) }}">
        <a class="nav-link" href="{{ route('govt-official.dashboard') }}">
            <i class="menu-icon mdi mdi-television"></i>
            <span class="menu-title">Dashboard</span>
        </a>
    </li>

    <li class="nav-item {{ active_class(['county-govt/cooperatives*']) }}">
        <a class="nav-link" href="{{ route('govt-official.cooperatives.show') }}">
            <i class="menu-icon mdi mdi-television"></i>
            <span class="menu-title">Cooperatives</span>
        </a>
    </li>
    <li class="nav-item {{ active_class(['county-govt/farmers*']) }}">
        <a class="nav-link" href="{{ route('govt-official.farmers.show') }}">
            <i class="menu-icon mdi mdi-television"></i>
            <span class="menu-title">Farmers</span>
        </a>
    </li>
    <li class="nav-item {{ active_class(['county-govt/millers*']) }}">
        <a class="nav-link" href="{{ route('govt-official.millers.show') }}">
            <i class="menu-icon mdi mdi-television"></i>
            <span class="menu-title">Millers</span>
        </a>
    </li>
    @endif

    @if($user && $user->hasRole('cooperative admin|employee'))
    <!-- <li class="nav-item {{ active_class(['dashboard']) }}">
            <a class="nav-link" href="{{ route('cooperative-admin.dashboard') }}">
                <i class="menu-icon mdi mdi-television"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li> -->


    @php
    $activeArr = ['cooperative-admin/branches*', 'cooperative/hr/branches*'];
    @endphp
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('cooperative-admin.dashboard') }}">
                <i class="ni ni-chart-pie-35 text-custom-green"></i> {{ __('Dashboard') }}
            </a>
        </li>

        <!-- Wet Mills CRM Dropdown -->
        <li class="nav-item">
            <a class="nav-link active" href="#wetmills-crm" data-toggle="collapse" role="button" aria-expanded="false"
                aria-controls="wetmills-crm">
                <i class="ni ni-building text-custom-green"></i>
                <span class="nav-link-text" style="color: #f4645f;">{{ __('Wet Mills CRM') }}</span>
            </a>
            <div class="collapse" id="wetmills-crm">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cooperative-admin.branches.mini-dashboard') }}">
                            {{ __('Dashboard') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('hr.branches.show') }}"> {{ __('Wet Mills') }}</a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Farmer / Suppliers CRM Dropdown -->
        <li class="nav-item">
            <a class="nav-link active" href="#farmers-crm" data-toggle="collapse" role="button" aria-expanded="false"
                aria-controls="farmers-crm">
                <i class="ni ni-single-02 text-custom-green"></i>
                <span class="nav-link-text" style="color: #f4645f;">{{ __('Farmer / Suppliers CRM') }}</span>
            </a>
            <div class="collapse" id="farmers-crm">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cooperative-admin.farmers.mini-dashboard') }}">
                            {{ __('Dashboard') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cooperative-admin.farmers.show') }}">
                            {{ __('Farmers') }}
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Collection CRM Dropdown -->
        <li class="nav-item">
            <a class="nav-link active" href="#collections-crm" data-toggle="collapse" role="button"
                aria-expanded="false" aria-controls="collections-crm">
                <i class="ni ni-archive-2 text-custom-green"></i>
                <span class="nav-link-text" style="color: #f4645f;">{{ __('Collection CRM') }}</span>
            </a>
            <div class="collapse" id="collections-crm">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cooperative-admin.collections.mini-dashboard') }}">
                            {{ __('Dashboard') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cooperative-admin.lots.show') }}">
                            {{ __('Lots') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cooperative-admin.collections.show') }}">
                            {{ __('Collections') }}
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Wallet Management Dropdown -->
        <li class="nav-item">
            <a class="nav-link active" href="#wallet-management" data-toggle="collapse" role="button"
                aria-expanded="false" aria-controls="wallet-management">
                <i class="ni ni-money-coins text-custom-green"></i>
                <span class="nav-link-text" style="color: #f4645f;">{{ __('Wallet Management') }}</span>
            </a>
            <div class="collapse" id="wallet-management">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cooperative-admin.wallet-management.dashboard') }}">
                            {{ __('Dashboard') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="{{ route('cooperative-admin.wallet-management.account-receivables') }}">
                            {{ __('Account Receivables') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cooperative-admin.wallet-management.account-payables') }}">
                            {{ __('Account Payables') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cooperative-admin.wallet-management.income') }}">
                            {{ __('Income') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cooperative-admin.wallet-management.expenses') }}">
                            {{ __('Expenses') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cooperative-admin.wallet-management.deposits') }}">
                            {{ __('Deposits') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cooperative-admin.wallet-management.withdrawals') }}">
                            {{ __('Withdrawals') }}
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Products / Raw Materials -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('cooperative-admin.products.show') }}">
                <i class="ni ni-tag text-custom-green"></i> {{ __('Products / Raw Materials ') }}
            </a>
        </li>

        <!-- Transactions Reports -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('cooperative-admin.transactions.show') }}">
                <i class="ni ni-sound-wave text-custom-green"></i> {{ __('Transactions Reports') }}
            </a>
        </li>

        <!-- Orders -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('cooperative-admin.orders.show') }}">
                <i class="ni ni-cart text-custom-green"></i> {{ __('Orders') }}
            </a>
        </li>

        <!-- Settings -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('cooperative-admin.settings.show') }}">
                <i class="ni ni-settings text-custom-green"></i> {{ __('Settings') }}
            </a>
        </li>

        <!-- Support -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('cooperative-admin.support.show') }}">
                <i class="ni ni-support-16 text-custom-green"></i> {{ __('Support') }}
            </a>
        </li>

        <!-- Chat -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('chat.index') }}">
                <i class="ni ni-chat-round text-custom-green"></i> {{ __('Chat') }}
            </a>
        </li>


        @if(has_right_permission(config('enums.system_modules')['HR Management']['employees'],
        config('enums.system_permissions')['view']))
        <!-- <li class="nav-item {{ active_class(['cooperative/hr/*employees']) }}">
            <a class="nav-link" href="{{ route('hr.employees.show') }}">
                <i class="menu-icon mdi mdi-television"></i>
                <span class="menu-title">Employees</span>
                {{config('enums.system_modules')['HR Management']['employees']}}
            </a>
        </li> -->
        @endif

        @if(can_view_module('User Management'))
        <!-- <li class="nav-item {!!  active_class(['cooperative/user-management/*']) !!} ">
            <a class="nav-link" data-toggle="collapse" href="#userManagement" aria-expanded="{!!  is_active_route(['cooperative/user-management/*'])  !!}" aria-controls="userManagement">
                <i class="menu-icon mdi mdi-account-multiple"></i>
                <span class="menu-title">User Management</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ show_class(['cooperative/user-management/*']) }}" id="userManagement">
                <ul class="nav flex-column sub-menu">
                    @if(has_right_permission(config('enums.system_modules')['User Management']['roles'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/user-management/roles']) }}">
                        <a class="nav-link" href="{{ route('cooperative.roles') }}">
                            {{config('enums.system_modules')['User Management']['roles']}}
                        </a>
                    </li>
                    @endif
                    @if(has_right_permission(config('enums.system_modules')['User Management']['roles'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/user-management/role-management']) }}">
                        <a class="nav-link" href="{{ route('cooperative.role-management') }}">
                            {{config('enums.system_modules')['User Management']['role_management']}}
                        </a>
                    </li>
                    @endif
                    @if(has_right_permission(config('enums.system_modules')['User Management']['roles'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/user-management/module/role-management']) }}">
                        <a class="nav-link" href="{{ route('cooperative.module-management') }}">
                            {{config('enums.system_modules')['User Management']['module_management']}}
                        </a>
                    </li>
                    @endif
                    @if(has_right_permission(config('enums.system_modules')['User Management']['roles'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/user-management/permissions']) }}">
                        <a class="nav-link" href="{{ route('cooperative.permissions') }}">
                            {{config('enums.system_modules')['User Management']['permissions']}}
                        </a>
                    </li>
                    @endif
                    @if(has_right_permission(config('enums.system_modules')['User Management']['role_permissions'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/user-management/role-permissions']) }}">
                        <a class="nav-link" href="{{ route('cooperative.role-permissions') }}">
                            {{config('enums.system_modules')['User Management']['role_permissions']}}
                        </a>
                    </li>
                    @endif
                    @if(has_right_permission(config('enums.system_modules')['User Management']['activity_log'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/user-management/activity-log']) }}">
                        <a class="nav-link" href="{{ route('cooperative.activity_log') }}">
                            {{config('enums.system_modules')['User Management']['activity_log']}}
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </li> -->
        @endif

        @if(can_view_module('HR Management'))
        <!-- <li class="nav-item {!!  active_class(['cooperative/hr/*']) !!} ">
            <a class="nav-link" data-toggle="collapse" href="#hrManagement" aria-expanded="{!!  is_active_route(['cooperative/hr/*'])  !!}" aria-controls="hrManagement">
                <i class="menu-icon mdi mdi mdi-account-network"></i>
                <span class="menu-title">HR Management</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ show_class(['cooperative/hr/*']) }}" id="hrManagement">
                <ul class="nav flex-column sub-menu">
                    @if(has_right_permission(config('enums.system_modules')['HR Management']['dashboard'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/hr/dashboard']) }}">
                        <a class="nav-link" href="{{ route('hr.dashboard') }}">
                            {{config('enums.system_modules')['HR Management']['dashboard']}}
                        </a>
                    </li>
                    @endif
                    @if(has_right_permission(config('enums.system_modules')['HR Management']['branches'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/hr/branches']) }}">
                        <a class="nav-link" href="{{ route('hr.branches.show') }}">
                            {{config('enums.system_modules')['HR Management']['branches']}}
                        </a>
                    </li>
                    @endif
                    @if(has_right_permission(config('enums.system_modules')['HR Management']['departments'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/hr/departments']) }}">
                        <a class="nav-link" href="{{ route('hr.departments.show') }}">
                            {{config('enums.system_modules')['HR Management']['departments']}}
                        </a>
                    </li>
                    @endif
                    @if(has_right_permission(config('enums.system_modules')['HR Management']['job_type'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/hr/employment-types']) }}">
                        <a class="nav-link" href="{{ route('hr.employment-types.show') }}">
                            {{config('enums.system_modules')['HR Management']['job_type']}}
                        </a>
                    </li>
                    @endif
                    @if(has_right_permission(config('enums.system_modules')['HR Management']['job_positions'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/hr/job-positions']) }}">
                        <a class="nav-link" href="{{ route('hr.job-positions.show') }}">
                            {{config('enums.system_modules')['HR Management']['job_positions']}}
                        </a>
                    </li>
                    @endif
                    @if(has_right_permission(config('enums.system_modules')['HR Management']['employees'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/hr/*employees']) }}">
                        <a class="nav-link" href="{{ route('hr.employees.show') }}">
                            {{config('enums.system_modules')['HR Management']['employees']}}
                        </a>
                    </li>
                    @endif
                    @if(has_right_permission(config('enums.system_modules')['HR Management']['payroll'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/hr/payroll']) }}">
                        <a class="nav-link" href="{{ route('hr.employees.payroll') }}">
                            {{config('enums.system_modules')['HR Management']['payroll']}}
                        </a>
                    </li>
                    @endif

                    @if(has_right_permission(config('enums.system_modules')['HR Management']['department_payroll'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/hr/payrolls/department']) }}">
                        <a class="nav-link" href="{{ route('hr.employees.payroll.department') }}">
                            {{config('enums.system_modules')['HR Management']['department_payroll']}}
                        </a>
                    </li>
                    @endif

                    @if(has_right_permission(config('enums.system_modules')['HR Management']['files'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/hr/employees/files']) }}">
                        <a class="nav-link" href="{{ route('hr.employees.files') }}">
                            {{config('enums.system_modules')['HR Management']['files']}}
                        </a>
                    </li>
                    @endif
                    @if(has_right_permission(config('enums.system_modules')['HR Management']['leave'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/hr/leaves']) }}">
                        <a class="nav-link" href="{{ route('hr.leaves.show') }}">
                            {{config('enums.system_modules')['HR Management']['leave']}}
                        </a>
                    </li>
                    @endif
                    @if(has_right_permission(config('enums.system_modules')['HR Management']['recruitment'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/hr/recruitments']) }}">
                        <a class="nav-link" href="{{ route('hr.recruitments.show') }}">
                            {{config('enums.system_modules')['HR Management']['recruitment']}}
                        </a>
                    </li>
                    @endif

                    @if(has_right_permission(config('enums.system_modules')['HR Management']['reports'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/hr/reports']) }}">
                        <a class="nav-link" href="{{ route('cooperative.hr.reports') }}">
                            {{config('enums.system_modules')['HR Management']['reports']}}
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </li> -->
        @endif

        @if(can_view_module('Bank Management'))
        <!-- <li class="nav-item {!!  active_class(['cooperative/bank/*']) !!} ">
            <a class="nav-link" data-toggle="collapse" href="#bankManagement" aria-expanded="{!!  is_active_route(['cooperative/bank/*'])  !!}" aria-controls="bankManagement">
                <i class="menu-icon mdi mdi-bank"></i>
                <span class="menu-title">Bank Management</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ show_class(['cooperative/bank/*']) }}" id="bankManagement">
                <ul class="nav flex-column sub-menu">
                    @if(has_right_permission(config('enums.system_modules')['Bank Management']['banks'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/bank/show']) }}">
                        <a class="nav-link" href="{{ route('cooperative.bank.show') }}">
                            {{config('enums.system_modules')['Bank Management']['banks']}}
                        </a>
                    </li>
                    @endif
                    @if(has_right_permission(config('enums.system_modules')['Bank Management']['branches'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/bank/bank_branches']) }}">
                        <a class="nav-link" href="{{ route('cooperative.bank_branch.show') }}">
                            {{config('enums.system_modules')['Bank Management']['branches']}}
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </li> -->
        @endif

        @if(can_view_module('Farmer CRM'))
        <!-- <li class="nav-item {!!  active_class(['cooperative/farm/*']) !!} ">
            <a class="nav-link" data-toggle="collapse" href="#herdManagement" aria-expanded="{!!  is_active_route(['cooperative/farm/*'])  !!}" aria-controls="heardManagement">
                <i class="menu-icon mdi mdi-cow"></i>
                <span class="menu-title">Farm Management</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ show_class(['cooperative/farm/*']) }}" id="herdManagement">
                <ul class="nav flex-column sub-menu">
                    @if(has_right_permission(config('enums.system_modules')['Farm Management']['dashboard'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/farm/dashboard']) }}">
                        <a class="nav-link" href="{{ route('cooperative.farm.mini-dashboard') }}">
                            {{config('enums.system_modules')['Farm Management']['dashboard']}}
                        </a>
                    </li>
                    @endif
                    @if(has_right_permission(config('enums.system_modules')['Farm Management']['breed_registration'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/farm/breeds']) }}">
                        <a class="nav-link" href="{{ route('cooperative.farm.breeds') }}">
                            {{config('enums.system_modules')['Farm Management']['breed_registration']}}
                        </a>
                    </li>
                    @endif
                    @if(has_right_permission(config('enums.system_modules')['Farm Management']['livestock_poultry'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/farm/livestock-poultry']) }}">
                        <a class="nav-link" href="{{ route('cooperative.farm.animals') }}">
                            {{config('enums.system_modules')['Farm Management']['livestock_poultry']}}
                        </a>
                    </li>
                    @endif
                    @if(has_right_permission(config('enums.system_modules')['Farm Management']['farm_units'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/farm/farm-units']) }}">
                        <a class="nav-link" href="{{ route('cooperative.farm-units') }}">
                            {{config('enums.system_modules')['Farm Management']['farm_units']}}
                        </a>
                    </li>
                    @endif
                    @if(has_right_permission(config('enums.system_modules')['Farm Management']['crop_details'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/farm/crops']) }}">
                        <a class="nav-link" href="{{ route('cooperative.farm.crops') }}">
                            {{config('enums.system_modules')['Farm Management']['crop_details']}}
                        </a>
                    </li>
                    @endif
                    @if(has_right_permission(config('enums.system_modules')['Farm Management']['calendar_stages'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/farm/crop-calendar-stage*']) }}">
                        <a class="nav-link" href="{{ route('cooperative.farm.crop-calendar-stages') }}">
                            {{config('enums.system_modules')['Farm Management']['calendar_stages']}}
                        </a>
                    </li>
                    @endif
                    @if(has_right_permission(config('enums.system_modules')['Farm Management']['farmer_calendar'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/farm/farmer-calendar*']) }}">
                        <a class="nav-link" href="{{ route('cooperative.farm.farmer-crops') }}">
                            {{config('enums.system_modules')['Farm Management']['farmer_calendar']}}
                        </a>
                    </li>
                    @endif

                    @if(has_right_permission(config('enums.system_modules')['Farm Management']['yield_config'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/farm/configure-expected-yields']) }}">
                        <a class="nav-link" href="{{ route('cooperative.configure-expected-yields') }}">
                            {{config('enums.system_modules')['Farm Management']['yield_config']}}
                        </a>
                    </li>
                    @endif
                    @if(has_right_permission(config('enums.system_modules')['Farm Management']['yields'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/farm/farmer-yields']) }}">
                        <a class="nav-link" href="{{ route('cooperative.farmers-yields') }}">
                            {{config('enums.system_modules')['Farm Management']['yields']}}
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </li> -->
        @endif

        <!-- <li class="nav-item {!!  active_class(['cooperative/collections*']) !!} ">
            <a class="nav-link" data-toggle="collapse" href="#collectionsManagement" aria-expanded="{!!  is_active_route(['cooperative/collections/*'])  !!}" aria-controls="collectionsManagement">
                <i class="menu-icon mdi mdi-chemical-weapon"></i>
                <span class="menu-title">Collections</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ show_class(['cooperative/collections*']) }}" id="collectionsManagement">
                <ul class="nav flex-column sub-menu">

                    @if(has_right_permission(config('enums.system_modules')['Collections']['dashboard'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/collections/dashboard']) }}">
                        <a class="nav-link" href="{{ route('cooperative.collections.reports') }}">
                            {{config('enums.system_modules')['Collections']['dashboard']}}
                        </a>
                    </li>
                    @endif

                    @if(has_right_permission(config('enums.system_modules')['Collections']['quality_std'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/collections/quality-standards']) }}">
                        <a class="nav-link" href="{{ route('cooperative.quality-standards.show') }}">
                            {{config('enums.system_modules')['Collections']['quality_std']}}
                        </a>
                    </li>
                    @endif

                    @if(has_right_permission(config('enums.system_modules')['Collections']['collect'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/collections']) }}">
                        <a class="nav-link" href="{{ route('cooperative.collections.show') }}">
                            {{config('enums.system_modules')['Collections']['collect']}}
                        </a>
                    </li>
                    @endif
                    @if(has_right_permission(config('enums.system_modules')['Collections']['submitted_collection'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/collections/submitted']) }}">
                        <a class="nav-link" href="{{ route('cooperative.submitted.collections') }}">
                            {{config('enums.system_modules')['Collections']['submitted_collection']}}
                        </a>
                    </li>
                    @endif
                    @if(has_right_permission(config('enums.system_modules')['Collections']['bulk_payment'], config('enums.system_permissions')['view']))
                    <li class="nav-item {{ active_class(['cooperative/collections/bulk-payment']) }}">
                        <a class="nav-link" href="{{ route('cooperative.collection.bulk-payment') }}">
                            {{config('enums.system_modules')['Collections']['bulk_payment']}}
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </li> -->
        @endif

        @if($user && $user->hasRole('miller admin'))
        <ul class="navbar-nav">
            <li class="nav-item {{ active_class(['miller-admin/dashboard']) }}">
                <a class="nav-link text-black" href="{{ route('miller-admin.dashboard') }}">
                    <i class="ni ni-chart-bar-32 text-green"></i> {{ __('Dashboard') }}
                </a>
            </li>
            <li class="nav-item {{ active_class(['miller-admin/warehouses*']) }}">
                <a class="nav-link" href="{{ route('miller-admin.warehouses.show') }}">
                    <i class="ni ni-building text-green"></i> {{ __('Warehouse') }}
                </a>
            </li>

            <!-- Marketplace Dropdown -->
            <li class="nav-item {!! active_class(['miller-admin/market-auction/*']) !!}">
                <a class="nav-link" href="#navbar-marketplace" data-toggle="collapse" role="button"
                    aria-expanded="{!! is_active_route(['miller-admin/market-auction/*']) !!}"
                    aria-controls="navbar-marketplace">
                    <i class="ni ni-basket text-green"></i> {{ __('Marketplace') }}
                </a>
                <div class="collapse {{ show_class(['miller-admin/market-auction/*']) }}" id="navbar-marketplace">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item {{ active_class(['market-auction/dashboard']) }}">
                            <a class="nav-link" href="{{ route('miller-admin.marketplace-dashboard') }}">
                                {{ __('Dashboard') }}
                            </a>
                        </li>
                        <!-- Products Item -->
                        <li class="nav-item {{ active_class(['market-auction/products']) }}">
                            <a class="nav-link" href="{{ route('miller-admin.marketplace-products') }}">
                                {{ __('Products') }}
                            </a>
                        </li>
                        <!-- Market Auction Item -->
                        <li class="nav-item {{ active_class(['miller-admin/market-auction/show']) }}">
                            <a class="nav-link" href="{{ route('miller-admin.market-auction.show') }}">
                                {{ __('Market Auction') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </li>


            <li class="nav-item {{ active_class(['miller-admin/orders*']) }}">
                <a class="nav-link" href="{{ route('miller-admin.orders.show') }}">
                    <i class="ni ni-delivery-fast text-green"></i> {{ __('Orders') }}
                </a>
            </li>

            <!-- Inventory Dropdown -->
            <li class="nav-item {!!  active_class(['miller-admin/inventory/*']) !!} ">
                <a class="nav-link" href="#navbar-inventory" data-toggle="collapse" role="button"
                    aria-expanded="{!! is_active_route(['miller-admin/inventory/*']) !!}"
                    aria-controls="navbar-inventory">
                    <i class="ni ni-box-2 text-green"></i>
                    <span class="nav-link-text">{{ __('Inventory') }}</span>
                </a>
                <div class="collapse {{ show_class(['miller-admin/inventory/*']) }}" id="navbar-inventory">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item {{ active_class(['miller-admin/inventory/pre-milled']) }}">
                            <a class="nav-link" href="{{ route('miller-admin.pre-milled-inventory.show') }}">
                                {{ __('Pre-milled Inventory') }}
                            </a>
                        </li>
                        <li class="nav-item {{ active_class(['miller-admin/inventory/milled']) }}">
                            <a class="nav-link" href="{{ route('miller-admin.milled-inventory.show') }}">
                                {{ __('Milled Inventory') }}
                            </a>
                        </li>
                        <li class="nav-item {{ active_class(['miller-admin/inventory/milled']) }}">
                            <a class="nav-link" href="{{ route('miller-admin.milled-inventory.show') }}">
                                {{ __('Inventory Grades') }}
                            </a>
                        </li>
                        <li class="nav-item {{ active_class(['miller-admin/inventory/final-products']) }}">
                            <a class="nav-link" href="{{ route('miller-admin.final-products.show') }}">
                                {{ __('Final Product') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Inventory Auction Dropdown -->
            <li class="nav-item {!! active_class(['miller-admin/inventory-auction*']) !!}">
                <a class="nav-link" href="#navbar-auction" data-toggle="collapse" role="button"
                    aria-expanded="{!! is_active_route(['miller-admin/inventory-auction*']) !!}"
                    aria-controls="navbar-auction">
                    <i class="ni ni-cart text-green"></i>
                    <span class="nav-link-text">{{ __('Inventory Auction') }}</span>
                </a>
                <div class="collapse {{ show_class(['miller-admin/inventory-auction*']) }}" id="navbar-auction">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item {{ active_class(['miller-admin/inventory-auction/customers*']) }}">
                            <a class="nav-link" href="{{ route('miller-admin.inventory-auction.list-customers') }}">
                                {{ __('Customers') }}
                            </a>
                        </li>
                        <li class="nav-item {{ active_class(['miller-admin/inventory-auction/quotations*']) }}">
                            <a class="nav-link" href="{{ route('miller-admin.inventory-auction.list-quotations') }}">
                                {{ __('Quotations') }}
                            </a>
                        </li>
                        <li class="nav-item {{ active_class(['miller-admin/inventory-auction/invoices*']) }}">
                            <a class="nav-link" href="{{ route('miller-admin.inventory-auction.list-invoices') }}">
                                {{ __('Invoices') }}
                            </a>
                        </li>
                        <li class="nav-item {{ active_class(['miller-admin/inventory-auction/receipts*']) }}">
                            <a class="nav-link" href="{{ route('miller-admin.inventory-auction.list-receipts') }}">
                                {{ __('Payment Receipt') }}
                            </a>
                        </li>
                        <li class="nav-item {{ active_class(['miller-admin/inventory-auction/sales*']) }}">
                            <a class="nav-link" href="{{ route('miller-admin.inventory-auction.list-sales') }}">
                                {{ __('Sales') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Wallet Management Dropdown -->
            <li class="nav-item {!!  active_class(['miller-admin/wallet-management/*']) !!} ">
                <a class="nav-link" href="#navbar-wallet" data-toggle="collapse" role="button"
                    aria-expanded="{!!  is_active_route(['miller-admin/wallet-management/*'])  !!}"
                    aria-controls="navbar-wallet">
                    <i class="ni ni-money-coins text-green"></i>
                    <span class="nav-link-text">{{ __('Wallet Management') }}</span>
                </a>
                <div class="collapse {{ show_class(['miller-admin/wallet-management/*']) }}" id="navbar-wallet">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item {{ active_class(['miller-admin/wallet-management/dashboard']) }}">
                            <a class="nav-link" href="{{ route('miller-admin.wallet-management.dashboard') }}">
                                {{ __('Dashboard') }}
                            </a>
                        </li>
                        <li class="nav-item {{ active_class(['miller-admin/wallet-management/account-receivables']) }}">
                            <a class="nav-link"
                                href="{{ route('miller-admin.wallet-management.account-receivables') }}">
                                {{ __('Account Receivables') }}
                            </a>
                        </li>
                        <li class="nav-item {{ active_class(['miller-admin/wallet-management/account-payables']) }}">
                            <a class="nav-link" href="{{ route('miller-admin.wallet-management.account-payables') }}">
                                {{ __('Account Payables') }}
                            </a>
                        </li>
                        <li class="nav-item {{ active_class(['miller-admin/wallet-management/income']) }}">
                            <a class="nav-link" href="{{ route('miller-admin.wallet-management.income') }}">
                                {{ __('Income') }}
                            </a>
                        </li>
                        <li class="nav-item {{ active_class(['miller-admin/wallet-management/expenses']) }}">
                            <a class="nav-link" href="{{ route('miller-admin.wallet-management.expenses') }}">
                                {{ __('Expenses') }}
                            </a>
                        </li>
                        <li class="nav-item {{ active_class(['miller-admin/wallet-management/deposits*']) }}">
                            <a class="nav-link" href="{{ route('miller-admin.wallet-management.deposits') }}">
                                {{ __('Deposits') }}
                            </a>
                        </li>
                        <li class="nav-item {{ active_class(['miller-admin/wallet-management/withdrawals*']) }}">
                            <a class="nav-link" href="{{ route('miller-admin.wallet-management.withdrawals') }}">
                                {{ __('Withdrawals') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </li>


            <li class="nav-item {{ active_class(['miller-admin/transactions*']) }}">
                <a class="nav-link" href="{{ route('miller-admin.transactions.show') }}">
                    <i class="ni ni-credit-card text-green"></i> {{ __('Transactions Report') }}
                </a>
            </li>
            <li class="nav-item {{ active_class(['miller-admin/support*']) }}">
                <a class="nav-link" href="{{ route('miller-admin.support.show') }}">
                    <i class="ni ni-support-16 text-green"></i> {{ __('Support') }}
                </a>
            </li>
            <li class="nav-item {{ active_class(['chat*']) }}">
                <a class="nav-link" href="{{ route('chat.index') }}">
                    <i class="ni ni-chat-round text-green"></i> {{ __('Chat') }}
                </a>
            </li>
            <li class="nav-item {{ active_class(['miller-admin/tracking-tree*']) }}">
                <a class="nav-link" href="{{ route('miller-admin.tracking-tree.show') }}">
                    <i class="ni ni-map-big text-green"></i> {{ __('Traceability Tree') }}
                </a>
            </li>
            @endif


            @if($user && $user->hasRole('farmer'))
            <li class="nav-item {{ active_class(['farmer/dashboard']) }}">
                <a class="nav-link" href="{{ route('farmer.dashboard') }}">
                    <i class="menu-icon mdi mdi-television"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>

            <li class="nav-item {{ active_class(['farmer/collections*']) }}">
                <a class="nav-link" href="{{ route('farmer.collections.show') }}">
                    <i class="menu-icon mdi mdi-cogs"></i>
                    <span class="menu-title">Collections</span>
                </a>
            </li>

            <li class="nav-item {{ active_class(['farmer/transactions*']) }}">
                <a class="nav-link" href="{{ route('farmer.transactions.show') }}">
                    <i class="menu-icon mdi mdi-help-circle-outline"></i>
                    <span class="menu-title">Transactions</span>
                </a>
            </li>
            @endif

            {{-- vet menu --}}
            @if($user && $user->hasRole('vet'))

            <li class="nav-item {{ active_class(['dashboard']) }}">
                <a class="nav-link" href="{{ url('dashboard') }}">
                    <i class="menu-icon mdi mdi-television"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>

            <li class="nav-item {!!  active_class(['vet*']) !!} ">
                <a class="nav-link" data-toggle="collapse" href="#vetSchedule"
                    aria-expanded="{!!  is_active_route(['vet*'])  !!}" aria-controls="vetSchedule">
                    <i class="menu-icon mdi mdi-calendar"></i>
                    <span class="menu-title">My Schedule</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse {{ show_class(['vet*']) }}" id="vetSchedule">
                    <ul class="nav flex-column sub-menu">

                        <li class="nav-item {{ active_class(['vet/my-bookings/show']) }}">
                            <a class="nav-link" href="{{ route('vet.my-bookings.show') }}">Bookings</a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif

            @if(env('APP_ENV') == 'localx' || env('APP_ENV') == 'development')
            <li class="nav-item {!!  active_class(['basic-ui/*']) !!} ">
                <a class="nav-link" data-toggle="collapse" href="#basic-ui"
                    aria-expanded="{!!  is_active_route(['basic-ui/*'])  !!}" aria-controls="basic-ui">
                    <i class="menu-icon mdi mdi-dna"></i>
                    <span class="menu-title">Basic UI Elements</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse {{ show_class(['basic-ui/*']) }}" id="basic-ui">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item {{ active_class(['basic-ui/buttons']) }}">
                            <a class="nav-link" href="{{ url('/basic-ui/buttons') }}">Buttons</a>
                        </li>
                        <li class="nav-item {{ active_class(['basic-ui/dropdowns']) }}">
                            <a class="nav-link" href="{{ url('/basic-ui/dropdowns') }}">Dropdowns</a>
                        </li>
                        <li class="nav-item {{ active_class(['basic-ui/typography']) }}">
                            <a class="nav-link" href="{{ url('/basic-ui/typography') }}">Typography</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item {{ active_class(['charts/chartjs']) }}">
                <a class="nav-link" href="{{ url('/charts/chartjs') }}">
                    <i class="menu-icon mdi mdi-chart-line"></i>
                    <span class="menu-title">Charts</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['tables/basic-table']) }}">
                <a class="nav-link" href="{{ url('/tables/basic-table') }}">
                    <i class="menu-icon mdi mdi-table-large"></i>
                    <span class="menu-title">Tables</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['icons/material']) }}">
                <a class="nav-link" href="{{ url('/icons/material') }}">
                    <i class="menu-icon mdi mdi-emoticon"></i>
                    <span class="menu-title">Icons</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['user-pages/*']) }}">
                <a class="nav-link" data-toggle="collapse" href="#user-pages"
                    aria-expanded="{{ is_active_route(['user-pages/*']) }}" aria-controls="user-pages">
                    <i class="menu-icon mdi mdi-lock-outline"></i>
                    <span class="menu-title">User Pages</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse {{ show_class(['user-pages/*']) }}" id="user-pages">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item {{ active_class(['user-pages/login']) }}">
                            <a class="nav-link" href="{{ url('/user-pages/login') }}">Login</a>
                        </li>
                        <li class="nav-item {{ active_class(['user-pages/register']) }}">
                            <a class="nav-link" href="{{ url('/user-pages/register') }}">Register</a>
                        </li>
                        <li class="nav-item {{ active_class(['user-pages/lock-screen']) }}">
                            <a class="nav-link" href="{{ url('/user-pages/lock-screen') }}">Lock
                                Screen</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link"
                    href="https://www.bootstrapdash.com/demo/star-laravel-free/documentation/documentation.html"
                    target="_blank">
                    <i class="menu-icon mdi mdi-file-outline"></i>
                    <span class="menu-title">Documentation</span>
                </a>
            </li>
            @endif
        </ul>
    </ul>
    </div>
</nav>