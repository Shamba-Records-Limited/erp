<nav class="sidebar sidebar-offcanvas dynamic-active-class-disabled" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile not-navigation-link">
            <div class="nav-link">
                <div class="user-wrapper">
                    @php $user = Auth::user(); @endphp
                    <div class="profile-image">
                        
                            <img src="{{ url('assets/images/avatar.png') }}" alt="profile image">
                        
                    </div>
                    <div class="text-wrapper">
                        <p class="profile-name"> @if($user)
                                {{ ucwords( strtolower($user->first_name)) }} {{ ucwords(strtolower($user->other_names)) }}
                            @endif</p>
                        <div class="dropdown" data-display="static">
                            <a href="#" class="nav-link d-flex user-switch-dropdown-toggler"
                               id="UsersettingsDropdown"
                               href="#" data-toggle="dropdown" aria-expanded="false">

                                @if($user)
                                    @php
                                        $roles = $user->getRoleNames();
                                    @endphp
                                    <small class="designation text-muted">
                                        @foreach($roles as $role)
                                            {{ ucwords(strtolower($role)) }}
                                        @endforeach
                                    </small>
                                    <span class="status-indicator online"></span>
                                @endif

                            </a>

                            <small class="designation text-muted">
                                {{$user ? ucwords(strtolower($user->cooperative->name)) : '' }}
                            </small>
                            <div class="dropdown-menu" aria-labelledby="UsersettingsDropdown">
                                <a class="dropdown-item p-0">
                                    <div class="d-flex border-bottom">
                                        <div class="py-3 px-4 d-flex align-items-center justify-content-center">
                                            <i class="mdi mdi-bookmark-plus-outline mr-0 text-gray"></i>
                                        </div>
                                        <div class="py-3 px-4 d-flex align-items-center justify-content-center border-left border-right">
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
                @if($user &&  $user->hasRole('admin'))
                    @php
                        $countries = \App\Country::count();
                    @endphp
                    @if($countries < 1)
                        <a class="btn btn-success btn-block text-white"
                           href="{{ route('countries-data') }}">
                            Populate Countries <i class="mdi mdi-flag"></i></a>
                    @endif
                    <br>
                    <a class="btn btn-danger btn-block text-white" href="{{ route('optimize') }}">
                        Optimize App <i class="mdi mdi-timelapse"></i></a>
                @endif
            </div>
        </li>

        @if($user && $user->hasRole('admin'))
            <li class="nav-item {{ active_class(['dashboard']) }}">
                <a class="nav-link" href="{{ url('dashboard') }}">
                    <i class="menu-icon mdi mdi-television"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['admin/cooperative/setup*']) }}">
                <a class="nav-link" href="{{ route('cooperative') }}">
                    <i class="menu-icon mdi mdi-cogs"></i>
                    <span class="menu-title">Cooperative</span>
                </a>
            </li>

            <li class="nav-item {!!  active_class(['admin/manage/*']) !!} ">
                <a class="nav-link" data-toggle="collapse" href="#moduleManagement"
                   aria-expanded="{!!  is_active_route(['admin/modules*'])  !!}"
                   aria-controls="moduleManagement">
                    <i class="menu-icon mdi mdi-settings"></i>
                    <span class="menu-title">Module Management</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse {{ show_class(['admin/manage/*']) }}" id="moduleManagement">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item {{ active_class(['admin/manage/modules']) }}">
                            <a class="nav-link"
                               href="{{ route('modules') }}">Modules</a>
                        </li>
                        <li class="nav-item {{ active_class(['admin/manage/sub-modules']) }}">
                            <a class="nav-link" href="{{ route('sub-modules') }}">Submodules</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item {{ active_class(['admin/cooperative/payroll*']) }}">
                <a class="nav-link" href="{{ route('cooperative.payroll-config') }}">
                    <i class="menu-icon mdi mdi-cogs"></i>
                    <span class="menu-title">Payroll Configuration</span>
                </a>
            </li>
        @endif

        @if($user && $user->hasRole('cooperative admin|employee'))
            <li class="nav-item {{ active_class(['dashboard']) }}">
                <a class="nav-link" href="{{ url('dashboard') }}">
                    <i class="menu-icon mdi mdi-television"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>

            @if(can_view_module('Farmer CRM'))
                <li class="nav-item {!!  active_class(['cooperative/farmer/*']) !!} ">
                    <a class="nav-link" data-toggle="collapse" href="#farmerManagement"
                       aria-expanded="{!!  is_active_route(['cooperative/farmer/*'])  !!}"
                       aria-controls="farmerManagement">
                        <i class="menu-icon mdi mdi-account-multiple"></i>
                        <span class="menu-title">Farmer CRM</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse {{ show_class(['cooperative/farmer/*']) }}"
                         id="farmerManagement">
                        <ul class="nav flex-column sub-menu">
                            @if(has_right_permission(config('enums.system_modules')['Farmer CRM']['dashboard'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/farmer/dashboard']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.farmer.mini-dashboard') }}">{{config('enums.system_modules')['Farmer CRM']['dashboard']}}</a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Farmer CRM']['routes'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/farmer/route']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.routes.show') }}">{{config('enums.system_modules')['Farmer CRM']['routes']}}</a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Farmer CRM']['farmers'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/farmer/show']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.farmers.show') }}">{{config('enums.system_modules')['Farmer CRM']['farmers']}}</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            @if(can_view_module('Product Management'))
                <li class="nav-item {!!  active_class(['cooperative/products/*']) !!} ">
                    <a class="nav-link" data-toggle="collapse" href="#productsManagement"
                       aria-expanded="{!!  is_active_route(['cooperative/products/*'])  !!}"
                       aria-controls="productdManagement">
                        <i class="menu-icon mdi mdi-flower"></i>
                        <span class="menu-title">Product Management</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse {{ show_class(['cooperative/products/*']) }}"
                         id="productsManagement">
                        <ul class="nav flex-column sub-menu">
                            @if(has_right_permission(config('enums.system_modules')['Product Management']['dashboard'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/products/mini-dashboard']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.product-mini-dashboard') }}">{{config('enums.system_modules')['Product Management']['dashboard']}}</a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Product Management']['units'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/products/units']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.units.show') }}">{{config('enums.system_modules')['Product Management']['units']}}</a>
                                </li>
                            @endif

                            @if(has_right_permission(config('enums.system_modules')['Product Management']['categories'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/products/categories']) }}">
                                    <a class="nav-link"
                                       href="{{route('cooperative.categories.show') }}">{{config('enums.system_modules')['Product Management']['categories']}}</a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Product Management']['products'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/products/show']) }}">
                                    <a class="nav-link"
                                       href="{{route('cooperative.products.show') }}">{{config('enums.system_modules')['Product Management']['products']}}</a>
                                </li>
                            @endif

                            @if(has_right_permission(config('enums.system_modules')['Product Management']['suppliers'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/products/suppliers*']) }}">
                                    <a class="nav-link"
                                       href="{{route('cooperative.products.suppliers.show') }}">{{config('enums.system_modules')['Product Management']['suppliers']}}</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            @if(can_view_module('Logistics'))
                <li class="nav-item {!!  active_class(['cooperative/logistics/*']) !!} ">
                    <a class="nav-link" data-toggle="collapse" href="#logistics"
                       aria-expanded="{!!  is_active_route(['cooperative/logistics/*'])  !!}"
                       aria-controls="logistics">
                        <i class="menu-icon mdi mdi-truck"></i>
                        <span class="menu-title">Logistics</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse {{ show_class(['cooperative/logistics/*']) }}"
                         id="logistics">
                        <ul class="nav flex-column sub-menu">
                            @if(has_right_permission(config('enums.system_modules')['Logistics']['trip_management'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/logistics/trips*']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.logistics.dashboard') }}">
                                        {{config('enums.system_modules')['Logistics']['dashboard']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Logistics']['vehicle_types'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/logistics/vehicle_types']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.logistics.vehicle_types') }}">
                                        {{config('enums.system_modules')['Logistics']['vehicle_types']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Logistics']['vehicles'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/logistics/vehicles']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.logistics.vehicles') }}">
                                        {{config('enums.system_modules')['Logistics']['vehicles']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Logistics']['transport_providers'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/logistics/transporters*']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.logistics.transporters') }}">
                                        {{config('enums.system_modules')['Logistics']['transport_providers']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Logistics']['weighbridge'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/logistics/weighbridges*']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.logistics.weighbridges') }}">
                                        {{config('enums.system_modules')['Logistics']['weighbridge']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Logistics']['trip_management'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/logistics/trips*']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.logistics.trips') }}">
                                        {{config('enums.system_modules')['Logistics']['trip_management']}}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            @if(can_view_module('Collections'))
                <li class="nav-item {!!  active_class(['cooperative/collections*']) !!} ">
                    <a class="nav-link" data-toggle="collapse" href="#collectionsManagement"
                       aria-expanded="{!!  is_active_route(['cooperative/collections/*'])  !!}"
                       aria-controls="collectionsManagement">
                        <i class="menu-icon mdi mdi-chemical-weapon"></i>
                        <span class="menu-title">Collections</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse {{ show_class(['cooperative/collections*']) }}"
                         id="collectionsManagement">
                        <ul class="nav flex-column sub-menu">

                            @if(has_right_permission(config('enums.system_modules')['Collections']['dashboard'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/collections/dashboard']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.collections.reports') }}">
                                        {{config('enums.system_modules')['Collections']['dashboard']}}
                                    </a>
                                </li>
                            @endif

                            @if(has_right_permission(config('enums.system_modules')['Collections']['quality_std'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/collections/quality-standards']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.quality-standards.show') }}">
                                        {{config('enums.system_modules')['Collections']['quality_std']}}
                                    </a>
                                </li>
                            @endif

                            @if(has_right_permission(config('enums.system_modules')['Collections']['collect'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/collections']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.collections.show') }}">
                                        {{config('enums.system_modules')['Collections']['collect']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Collections']['submitted_collection'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/collections/submitted']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.submitted.collections') }}">
                                        {{config('enums.system_modules')['Collections']['submitted_collection']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Collections']['bulk_payment'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/collections/bulk-payment']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.collection.bulk-payment') }}">
                                        {{config('enums.system_modules')['Collections']['bulk_payment']}}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            @if(can_view_module('Bank Management'))
                <li class="nav-item {!!  active_class(['cooperative/bank/*']) !!} ">
                    <a class="nav-link" data-toggle="collapse" href="#bankManagement"
                       aria-expanded="{!!  is_active_route(['cooperative/bank/*'])  !!}"
                       aria-controls="bankManagement">
                        <i class="menu-icon mdi mdi-bank"></i>
                        <span class="menu-title">Bank Management</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse {{ show_class(['cooperative/bank/*']) }}"
                         id="bankManagement">
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
                                    <a class="nav-link"
                                       href="{{ route('cooperative.bank_branch.show') }}">
                                        {{config('enums.system_modules')['Bank Management']['branches']}}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif
            @if(can_view_module('Farm Management'))
                <li class="nav-item {!!  active_class(['cooperative/farm/*']) !!} ">
                    <a class="nav-link" data-toggle="collapse" href="#herdManagement"
                       aria-expanded="{!!  is_active_route(['cooperative/farm/*'])  !!}"
                       aria-controls="heardManagement">
                        <i class="menu-icon mdi mdi-cow"></i>
                        <span class="menu-title">Farm Management</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse {{ show_class(['cooperative/farm/*']) }}"
                         id="herdManagement">
                        <ul class="nav flex-column sub-menu">
                            @if(has_right_permission(config('enums.system_modules')['Farm Management']['dashboard'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/farm/dashboard']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.farm.mini-dashboard') }}">
                                        {{config('enums.system_modules')['Farm Management']['dashboard']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Farm Management']['breed_registration'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/farm/breeds']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.farm.breeds') }}">
                                        {{config('enums.system_modules')['Farm Management']['breed_registration']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Farm Management']['livestock_poultry'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/farm/livestock-poultry']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.farm.animals') }}">
                                        {{config('enums.system_modules')['Farm Management']['livestock_poultry']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Farm Management']['farm_units'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/farm/farm-units']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.farm-units') }}">
                                        {{config('enums.system_modules')['Farm Management']['farm_units']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Farm Management']['crop_details'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/farm/crops']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.farm.crops') }}">
                                        {{config('enums.system_modules')['Farm Management']['crop_details']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Farm Management']['calendar_stages'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/farm/crop-calendar-stage*']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.farm.crop-calendar-stages') }}">
                                        {{config('enums.system_modules')['Farm Management']['calendar_stages']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Farm Management']['farmer_calendar'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/farm/farmer-calendar*']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.farm.farmer-crops') }}">
                                        {{config('enums.system_modules')['Farm Management']['farmer_calendar']}}
                                    </a>
                                </li>
                            @endif

                            @if(has_right_permission(config('enums.system_modules')['Farm Management']['yield_config'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/farm/configure-expected-yields']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.configure-expected-yields') }}">
                                        {{config('enums.system_modules')['Farm Management']['yield_config']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Farm Management']['yields'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/farm/farmer-yields']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.farmers-yields') }}">
                                        {{config('enums.system_modules')['Farm Management']['yields']}}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            @if(can_view_module('Disease Management'))
                <li class="nav-item {!!  active_class(['cooperative/disease*']) !!} ">
                    <a class="nav-link" data-toggle="collapse" href="#diseaseManagement"
                       aria-expanded="{!!  is_active_route(['cooperative/herd/*'])  !!}"
                       aria-controls="diseaseManagement">
                        <i class="menu-icon mdi mdi-alert-outline"></i>
                        <span class="menu-title">Disease Management</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse {{ show_class(['cooperative/disease/*']) }}"
                         id="diseaseManagement">
                        <ul class="nav flex-column sub-menu">
                            @if(has_right_permission(config('enums.system_modules')['Disease Management']['dashboard'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/disease/mini-dashboard']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.disease.mini-dashboard') }}">
                                        {{config('enums.system_modules')['Disease Management']['dashboard']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Disease Management']['categories'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/disease/categories']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.disease.categories') }}">
                                        {{config('enums.system_modules')['Disease Management']['categories']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Disease Management']['diseases'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/disease/show']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.disease.show') }}">
                                        {{config('enums.system_modules')['Disease Management']['diseases']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Disease Management']['disease_cases'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/disease/case*']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.disease.reported_cases') }}">
                                        {{config('enums.system_modules')['Disease Management']['disease_cases']}}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            @if(can_view_module('Vet'))
                <li class="nav-item {!!  active_class(['cooperative/vet*']) !!} ">
                    <a class="nav-link" data-toggle="collapse" href="#vetManagement"
                       aria-expanded="{!!  is_active_route(['cooperative/vet/*'])  !!}"
                       aria-controls="vetManagement">
                        <i class="menu-icon mdi mdi-stethoscope"></i>
                        <span class="menu-title">Vet & Extension Services</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse {{ show_class(['cooperative/vet/*']) }}"
                         id="vetManagement">
                        <ul class="nav flex-column sub-menu">
                            @if(has_right_permission(config('enums.system_modules')['Vet & Extension Services']['services'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/vet/services/show']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.vet.service.show') }}">
                                        {{config('enums.system_modules')['Vet & Extension Services']['services']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Vet & Extension Services']['items'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/vet/items/show']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.vet.items.show') }}">
                                        {{config('enums.system_modules')['Vet & Extension Services']['items']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Vet & Extension Services']['vets'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/vet/show']) }}">
                                    <a class="nav-link" href="{{ route('cooperative.vet.show') }}">
                                        {{config('enums.system_modules')['Vet & Extension Services']['vets']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Vet & Extension Services']['bookings'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/vet/bookings/show']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.vet.bookings.show') }}">
                                        {{config('enums.system_modules')['Vet & Extension Services']['bookings']}}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            @if(can_view_module('HR Management'))
                <li class="nav-item {!!  active_class(['cooperative/hr/*']) !!} ">
                    <a class="nav-link" data-toggle="collapse" href="#hrManagement"
                       aria-expanded="{!!  is_active_route(['cooperative/hr/*'])  !!}"
                       aria-controls="hrManagement">
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
                                    <a class="nav-link"
                                       href="{{ route('hr.employment-types.show') }}">
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
                                    <a class="nav-link"
                                       href="{{ route('hr.employees.payroll.department') }}">
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
                                    <a class="nav-link"
                                       href="{{ route('cooperative.hr.reports') }}">
                                        {{config('enums.system_modules')['HR Management']['reports']}}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            @if(can_view_module('User Management'))
                <li class="nav-item {!!  active_class(['cooperative/user-management/*']) !!} ">
                    <a class="nav-link" data-toggle="collapse" href="#userManagement"
                       aria-expanded="{!!  is_active_route(['cooperative/user-management/*'])  !!}"
                       aria-controls="userManagement">
                        <i class="menu-icon mdi mdi-account-multiple"></i>
                        <span class="menu-title">User Management</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse {{ show_class(['cooperative/user-management/*']) }}"
                         id="userManagement">
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
                                    <a class="nav-link"
                                       href="{{ route('cooperative.role-management') }}">
                                        {{config('enums.system_modules')['User Management']['role_management']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['User Management']['roles'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/user-management/module/role-management']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.module-management') }}">
                                        {{config('enums.system_modules')['User Management']['module_management']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['User Management']['roles'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/user-management/permissions']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.permissions') }}">
                                        {{config('enums.system_modules')['User Management']['permissions']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['User Management']['role_permissions'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/user-management/role-permissions']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.role-permissions') }}">
                                        {{config('enums.system_modules')['User Management']['role_permissions']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['User Management']['activity_log'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/user-management/activity-log']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.activity_log') }}">
                                        {{config('enums.system_modules')['User Management']['activity_log']}}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif
            @if(can_view_module('Procurement'))
                <li class="nav-item {!!  active_class(['cooperative/procurement/*']) !!} ">
                    <a class="nav-link" data-toggle="collapse" href="#procurement"
                       aria-expanded="{!!  is_active_route(['cooperative/procurement/*'])  !!}"
                       aria-controls="procurement">
                        <i class="menu-icon mdi mdi-clipboard-list"></i>
                        <span class="menu-title">Procurement</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse {{ show_class(['cooperative/procurement/*']) }}"
                         id="procurement">
                        <ul class="nav flex-column sub-menu">
                            @if(has_right_permission(config('enums.system_modules')['Procurement']['store'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/procurement/store*']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.manufacturing.stores') }}">
                                        {{config('enums.system_modules')['Procurement']['store']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Procurement']['suppliers'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/procurement/suppliers']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.suppliers') }}">
                                        {{config('enums.system_modules')['Procurement']['suppliers']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Procurement']['purchase_orders'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/procurement/supplies']) }}">
                                    <a class="nav-link"
                                       href="{{ route('manufacturing.supplies') }}">
                                        {{config('enums.system_modules')['Procurement']['purchase_orders']}}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            @if(can_view_module('Manufacturing'))
                <li class="nav-item {!!  active_class(['cooperative/manufacturing/*']) !!} ">
                    <a class="nav-link" data-toggle="collapse" href="#manufacturing"
                       aria-expanded="{!!  is_active_route(['cooperative/manufacturing/*'])  !!}"
                       aria-controls="manufacturing">
                        <i class="menu-icon mdi mdi-factory"></i>
                        <span class="menu-title">Manufacturing</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse {{ show_class(['cooperative/manufacturing/*']) }}"
                         id="manufacturing">
                        <ul class="nav flex-column sub-menu">
                            @if(has_right_permission(config('enums.system_modules')['Manufacturing']['reports'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/manufacturing/reports']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.manufacturing.reports') }}">
                                        {{config('enums.system_modules')['Manufacturing']['reports']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Manufacturing']['final_products'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/manufacturing/products']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.manufacturings.show') }}">
                                        {{config('enums.system_modules')['Manufacturing']['final_products']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Procurement']['store'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/manufacturing/store*']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.manufacturing.stores') }}">
                                        {{config('enums.system_modules')['Procurement']['store']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Procurement']['suppliers'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/manufacturing/suppliers']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.suppliers') }}">
                                        {{config('enums.system_modules')['Procurement']['suppliers']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Manufacturing']['raw_materials'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/manufacturing/rawmaterials']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.manufacturings.rawmaterials') }}">
                                        {{config('enums.system_modules')['Manufacturing']['raw_materials']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Procurement']['purchase_orders'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/manufacturing/supplies']) }}">
                                    <a class="nav-link"
                                       href="{{ route('manufacturing.supplies') }}">
                                        {{config('enums.system_modules')['Procurement']['purchase_orders']}}
                                    </a>
                                </li>
                            @endif

                            @if(has_right_permission(config('enums.system_modules')['Manufacturing']['production'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/manufacturing/production']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.manufacturing.production') }}">
                                        {{config('enums.system_modules')['Manufacturing']['production']}}
                                    </a>
                                </li>
                            @endif

                            @if(has_right_permission(config('enums.system_modules')['Manufacturing']['expired_stock'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/manufacturing/production/expired-stock']) }}">
                                    <a class="nav-link"
                                       href="{{ route('manufacturing.production.expired-stock') }}">
                                        {{config('enums.system_modules')['Manufacturing']['expired_stock']}}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif
            @if(can_view_module('Customer Management'))
                <li class="nav-item {{ active_class(['cooperative/customer*']) }}">
                    <a class="nav-link" data-toggle="collapse" href="#customer_management"
                       aria-expanded="{!!  is_active_route(['cooperative/customer*'])  !!}"
                       aria-controls="customer_management">
                        <i class="menu-icon mdi mdi-contact-mail"></i>
                        <span class="menu-title">{{config('enums.system_modules')['Customer Management']['crm']}}</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse {{ show_class(['cooperative/customer*']) }}"
                         id="customer_management">
                        <ul class="nav flex-column sub-menu">
                            @if(has_right_permission(config('enums.system_modules')['Customer Management']['customers'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/customer/registered']) }}">
                                    <a class="nav-link" href="{{ route('cooperative.customers') }}">
                                        <span class="menu-title"> {{config('enums.system_modules')['Customer Management']['customers']}}</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            @if(can_view_module('Sales'))
                <li class="nav-item {!!  active_class(['cooperative/sales/*']) !!} ">
                    <a class="nav-link" data-toggle="collapse" href="#sales"
                       aria-expanded="{!!  is_active_route(['cooperative/sales/*'])  !!}"
                       aria-controls="sales">
                        <i class="menu-icon mdi mdi mdi-sale"></i>
                        <span class="menu-title">Sales</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse {{ show_class(['cooperative/sales/*']) }}" id="sales">
                        <ul class="nav flex-column sub-menu">
                            @if(has_right_permission(config('enums.system_modules')['Sales']['invoice'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/sales/pos']) }}">
                                    <a class="nav-link" href="{{ route('sales.pos') }}">
                                        {{config('enums.system_modules')['Sales']['invoice']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Sales']['void_invoices'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/sales/void-ivoices*']) }}">
                                    <a class="nav-link"
                                       href="{{ route('sales.pos.void-invoices') }}">
                                        {{config('enums.system_modules')['Sales']['void_invoices']}}
                                    </a>
                                </li>
                            @endif


                            @if(has_right_permission(config('enums.system_modules')['Sales']['quotation'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/sales/quotation']) }}">
                                    <a class="nav-link" href="{{ route('sales.quotation') }}">
                                        {{config('enums.system_modules')['Sales']['quotation']}}
                                    </a>
                                </li>
                            @endif

                            @if(has_right_permission(config('enums.system_modules')['Sales']['returned_items'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/sales/returned-items']) }}">
                                    <a class="nav-link" href="{{ route('sales.returned-items') }}">
                                        {{config('enums.system_modules')['Sales']['returned_items']}}
                                    </a>
                                </li>
                            @endif

                            @if(has_right_permission(config('enums.system_modules')['Sales']['reports'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/sales/reports']) }}">
                                    <a class="nav-link" href="{{ route('sales.reports') }}">
                                        {{config('enums.system_modules')['Sales']['reports']}}
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </div>

                </li>
            @endif

            @if(can_view_module('Accounting'))
                <li class="nav-item {!!  active_class(['cooperative/accounting/*']) !!} ">
                    <a class="nav-link" data-toggle="collapse" href="#wallet"
                       aria-expanded="{!!  is_active_route(['cooperative/accounting/*'])  !!}"
                       aria-controls="wallet">
                        <i class="menu-icon mdi mdi mdi-calculator"></i>
                        <span class="menu-title">Accounting</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse {{ show_class(['cooperative/accounting/*']) }}"
                         id="wallet">
                        <ul class="nav flex-column sub-menu">
                            @if(has_right_permission(config('enums.system_modules')['Accounting']['wallet'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/accounting/wallet*']) }}">
                                    <a class="nav-link" href="{{ route('cooperative.wallet') }}">
                                        {{config('enums.system_modules')['Accounting']['wallet']}}
                                    </a>
                                </li>
                            @endif

                            @if(has_right_permission(config('enums.system_modules')['Accounting']['charts_of_account'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/accounting/charts_of_account']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.accounting.charts_of_account') }}">
                                        {{config('enums.system_modules')['Accounting']['charts_of_account']}}
                                    </a>
                                </li>
                            @endif

                            @if(has_right_permission(config('enums.system_modules')['Accounting']['accounting_rules'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/accounting/rules']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.accounting.rules') }}">
                                        {{config('enums.system_modules')['Accounting']['accounting_rules']}}
                                    </a>
                                </li>
                            @endif

                            @if(has_right_permission(config('enums.system_modules')['Accounting']['journal_entries'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/accounting/journal_entries']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.accounting.journal_entries') }}">
                                        {{config('enums.system_modules')['Accounting']['journal_entries']}}
                                    </a>
                                </li>
                            @endif

                            @if(has_right_permission(config('enums.system_modules')['Accounting']['asset'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/accounting/property']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.accounting.property.index') }}">
                                        {{config('enums.system_modules')['Accounting']['asset']}}
                                    </a>
                                </li>
                            @endif

                            @if(has_right_permission(config('enums.system_modules')['Accounting']['budget'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/accounting/budget']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.accounting.budget.index') }}">
                                        {{config('enums.system_modules')['Accounting']['budget']}}
                                    </a>
                                </li>
                            @endif

                            @if(has_right_permission(config('enums.system_modules')['Accounting']['reports'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/accounting/reports*']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.accounting.reports') }}">
                                        {{config('enums.system_modules')['Accounting']['reports']}}
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </div>
                </li>
            @endif

            @if(can_view_module('Financial Products'))
                <li class="nav-item {!!  active_class(['cooperative/financial-products/*']) !!} ">
                    <a class="nav-link" data-toggle="collapse" href="#financial-products"
                       aria-expanded="{!!  is_active_route(['cooperative/financial-products/*'])  !!}"
                       aria-controls="financial-products">
                        <i class="menu-icon mdi mdi-cogs"></i>
                        <span class="menu-title">Financial Products</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse {{ show_class(['cooperative/financial-products/*']) }}"
                         id="financial-products">
                        <ul class="nav flex-column sub-menu">
                            @if(has_right_permission(config('enums.system_modules')['Financial Products']['dashboard'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/financial-products/dashboard']) }}">
                                    <a class="nav-link"
                                       href="{{ route('financial_products.dashboard') }}">
                                        {{config('enums.system_modules')['Financial Products']['dashboard']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Financial Products']['loan_products'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/financial-products/loan-configs']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.loan_configs') }}">
                                        {{config('enums.system_modules')['Financial Products']['loan_products']}}
                                    </a>
                                </li>
                            @endif

                            @if(has_right_permission(config('enums.system_modules')['Financial Products']['saving_types'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/financial-products/saving-types']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.saving_types') }}">
                                        {{config('enums.system_modules')['Financial Products']['saving_types']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Financial Products']['limit_rate_setting'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/financial-products/limit-rate-config']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.limit-rate.config') }}">
                                        {{config('enums.system_modules')['Financial Products']['limit_rate_setting']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Financial Products']['loan_application'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/financial-products/loaned-farmers']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.loaned-farmers') }}">
                                        {{config('enums.system_modules')['Financial Products']['loan_application']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Financial Products']['current_savings'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/financial-products/farmer-savings']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.farmer-savings') }}">
                                        {{config('enums.system_modules')['Financial Products']['current_savings']}}
                                    </a>
                                </li>
                            @endif

                            @if(has_right_permission(config('enums.system_modules')['Financial Products']['loan_defaulters'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/financial-products/loan/defaulters']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.loan.defaulters') }}">
                                        {{config('enums.system_modules')['Financial Products']['loan_defaulters']}}
                                    </a>
                                </li>
                            @endif

                            @if(has_right_permission(config('enums.system_modules')['Financial Products']['loan_repayments'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/financial-products/loan/repayments']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.loan.repayments') }}">
                                        {{config('enums.system_modules')['Financial Products']['loan_repayments']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Financial Products']['interest'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/financial-products/loan/interest']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.loan.interest') }}">
                                        {{config('enums.system_modules')['Financial Products']['interest']}}
                                    </a>
                                </li>
                            @endif

                            @if(has_right_permission(config('enums.system_modules')['Financial Products']['group_loan_type'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/financial-products/group-loan-config']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.group.loan.config') }}">
                                        {{config('enums.system_modules')['Financial Products']['group_loan_setting']}}
                                    </a>
                                </li>
                            @endif

                            @if(has_right_permission(config('enums.system_modules')['Financial Products']['group_loan_type'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/financial-products/group-loan-types']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.group_loan_types') }}">
                                        {{config('enums.system_modules')['Financial Products']['group_loan_type']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Financial Products']['group_loans'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/financial-products/group-loans']) }}">
                                    <a class="nav-link" href="{{ route('admin.group.loans') }}">
                                        {{config('enums.system_modules')['Financial Products']['group_loans']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Financial Products']['group_loan_repayments'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/financial-products/group-loan-repayments']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.group.loan.repayments') }}">
                                        {{config('enums.system_modules')['Financial Products']['group_loan_repayments']}}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            @if(can_view_module('Insurance Product'))
                <li class="nav-item {!!  active_class(['cooperative/insurance/*']) !!} ">
                    <a class="nav-link" data-toggle="collapse" href="#insurance"
                       aria-expanded="{!!  is_active_route(['cooperative/insurance/*'])  !!}"
                       aria-controls="insurance">
                        <i class="menu-icon mdi mdi-security"></i>
                        <span class="menu-title">Insurance Product</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse {{ show_class(['cooperative/insurance/*']) }}"
                         id="insurance">
                        <ul class="nav flex-column sub-menu">
                            @if(has_right_permission(config('enums.system_modules')['Insurance Product']['product_benefits'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/insurance/benefits']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.insurance.benefits') }}">
                                        {{config('enums.system_modules')['Insurance Product']['product_benefits']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Insurance Product']['product_premiums'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/insurance/products']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.insurance.products') }}">
                                        {{config('enums.system_modules')['Insurance Product']['product_premiums']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Insurance Product']['premium_adjustments'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/insurance/config/premium-adjustments']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.insurance.config.premium-adjustments') }}">
                                        {{config('enums.system_modules')['Insurance Product']['premium_adjustments']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Insurance Product']['valuation'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/insurance/valuations']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.insurance.valuations') }}">
                                        {{config('enums.system_modules')['Insurance Product']['valuation']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Insurance Product']['insurance_subscription'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/insurance/subscription*']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.insurance.subscriptions') }}">
                                        {{config('enums.system_modules')['Insurance Product']['insurance_subscription']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Insurance Product']['product_limit'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/insurance/claim-limits']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.claim-limits') }}">
                                        {{config('enums.system_modules')['Insurance Product']['product_limit']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Insurance Product']['claims'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/insurance/claims']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.insurance.claims') }}">
                                        {{config('enums.system_modules')['Insurance Product']['claims']}}
                                    </a>
                                </li>
                            @endif
                            @if(has_right_permission(config('enums.system_modules')['Insurance Product']['reports'], config('enums.system_permissions')['view']))
                                <li class="nav-item {{ active_class(['cooperative/insurance/transaction-history']) }}">
                                    <a class="nav-link"
                                       href="{{ route('cooperative.insurance.trxn-hisory') }}">
                                        {{config('enums.system_modules')['Insurance Product']['reports']}}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif
        @endif

        {{--    farmer menu --}}
        @if($user && $user->hasRole('farmer'))
            <li class="nav-item {{ active_class(['dashboard']) }}">
                <a class="nav-link" href="{{ url('dashboard') }}">
                    <i class="menu-icon mdi mdi-television"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item {!!  active_class(['farmer/collections*']) !!} ">
                <a class="nav-link" data-toggle="collapse" href="#FarmerCollectionsManagement"
                   aria-expanded="{!!  is_active_route(['farmer/collections*'])  !!}"
                   aria-controls="FarmerCollectionsManagement">
                    <i class="menu-icon mdi mdi-chemical-weapon"></i>
                    <span class="menu-title">Collections</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse {{ show_class(['farmer/collections*']) }}"
                     id="FarmerCollectionsManagement">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item {{ active_class(['farmer/collections/reports']) }}">
                            <a class="nav-link" href="{{ route('farmer.collections.reports') }}">Reports</a>
                        </li>
                        <li class="nav-item {{ active_class(['farmer/collections']) }}">
                            <a class="nav-link" href="{{ route('farmer.collections') }}">Collect</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item {!!  active_class(['farmer/farm/*']) !!} ">
                <a class="nav-link" data-toggle="collapse" href="#FarmerFarmManagement"
                   aria-expanded="{!!  is_active_route(['/farmer/farm/*'])  !!}"
                   aria-controls="FarmerFarmManagement">
                    <i class="menu-icon mdi mdi-cow"></i>
                    <span class="menu-title">Farm Management</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse {{ show_class(['farmer/farm/*']) }}" id="FarmerFarmManagement">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item {{ active_class(['farmer/farm/breeds']) }}">
                            <a class="nav-link" href="{{ route('farm.breeds') }}">Breeds</a>
                        </li>
                        <li class="nav-item {{ active_class(['farmer/farm/livestock']) }}">
                            <a class="nav-link" href="{{ route('farm.livestock') }}">Livestock/Poultry</a>
                        </li>
                        <li class="nav-item {{ active_class(['farmer/farm/farm-units']) }}">
                            <a class="nav-link" href="{{ route('farm.farm-units') }}">Farm Units</a>
                        </li>
                        <li class="nav-item {{ active_class(['farmer/farm/crops']) }}">
                            <a class="nav-link" href="{{ route('farm.crops') }}">Supported Crops</a>
                        </li>
                        <li class="nav-item {{ active_class(['farmer/farm/crop-calendar-stages']) }}">
                            <a class="nav-link" href="{{ route('farm.crop-calendar-stages') }}">Crop
                                Calendar Stages</a>
                        </li>
                        <li class="nav-item {{ active_class(['farmer/farm/crop-stages*']) }}">
                            <a class="nav-link" href="{{ route('farm.crop-stages') }}">Crop
                                Stages</a>
                        </li>
                        <li class="nav-item {{ active_class(['farmer/farm/expected-yields']) }}">
                            <a class="nav-link" href="{{ route('farm.expected-yields') }}">Yield
                                Expectations</a>
                        </li>
                        <li class="nav-item {{ active_class(['farmer/farm/my-yields']) }}">
                            <a class="nav-link" href="{{ route('farm.yields') }}">My Yields</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item {!!  active_class(['farmer/disease*']) !!} ">
                <a class="nav-link" data-toggle="collapse" href="#FarmerDiseaseManagement"
                   aria-expanded="{!!  is_active_route(['farmer/disease*'])  !!}"
                   aria-controls="FarmerDiseaseManagement">
                    <i class="menu-icon mdi mdi-alert-outline"></i>
                    <span class="menu-title">Disease Management</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse {{ show_class(['farmer/disease*']) }}"
                     id="FarmerDiseaseManagement">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item {{ active_class(['farmer/disease/mini-dashboard']) }}">
                            <a class="nav-link"
                               href="{{ route('disease.mini-dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item {{ active_class(['farmer/disease/categories']) }}">
                            <a class="nav-link"
                               href="{{ route('disease.categories') }}">Categories</a>
                        </li>
                        <li class="nav-item {{ active_class(['farmer/disease']) }}">
                            <a class="nav-link" href="{{ route('diseases') }}">Diseases</a>
                        </li>
                        <li class="nav-item {{ active_class(['farmer/disease/case*']) }}">
                            <a class="nav-link" href="{{ route('disease.cases') }}">Reported
                                Cases</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item {!!  active_class(['farmer/vet*']) !!} ">
                <a class="nav-link" data-toggle="collapse" href="#FarmerVetManagement"
                   aria-expanded="{!!  is_active_route(['farmer/vet*'])  !!}"
                   aria-controls="FarmerVetManagement">
                    <i class="menu-icon mdi mdi-stethoscope"></i>
                    <span class="menu-title">Vet Services</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse {{ show_class(['farmer/vet*']) }}" id="FarmerVetManagement">
                    <ul class="nav flex-column sub-menu">

                        <li class="nav-item {{ active_class(['farmer/vet/my-bookings/show']) }}">
                            <a class="nav-link" href="{{ route('farmer.vet.my-bookings.show') }}">Bookings</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item {!!  active_class(['farmer/wallet/*']) !!} ">
                <a class="nav-link" data-toggle="collapse" href="#FarmerWallet"
                   aria-expanded="{!!  is_active_route(['farmer/wallet/*'])  !!}"
                   aria-controls="FarmerWallet">
                    <i class="menu-icon mdi mdi mdi-wallet"></i>
                    <span class="menu-title">Wallet</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse {{ show_class(['farmer/wallet/*']) }}" id="FarmerWallet">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item {{ active_class(['farmer/wallet/dashboard']) }}">
                            <a class="nav-link" href="{{ route('farmer.wallet.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item {{ active_class(['farmer/wallet/transactions']) }}">
                            <a class="nav-link" href="{{ route('farmer.wallet.transactions') }}">Transactions</a>
                        </li>
                        <li class="nav-item {{ active_class(['farmer/wallet/loans']) }}">
                            <a class="nav-link" href="{{ route('farmer.wallet.loans') }}">Loans</a>
                        </li>
                        <li class="nav-item {{ active_class(['farmer/wallet/savings']) }}">
                            <a class="nav-link"
                               href="{{ route('farmer.wallet.savings') }}">Savings</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item {!!  active_class(['farmer/insurance/*']) !!} ">
                <a class="nav-link" data-toggle="collapse" href="#FarmerInsurance"
                   aria-expanded="{!!  is_active_route(['farmer/insurance/*'])  !!}"
                   aria-controls="FarmerInsurance">
                    <i class="menu-icon mdi mdi mdi-security"></i>
                    <span class="menu-title">Insurance</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse {{ show_class(['farmer/insurance/*']) }}" id="FarmerInsurance">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item {{ active_class(['farmer/insurance/payment-mode-adjustments']) }}">
                            <a class="nav-link"
                               href="{{ route('insurance.payment-mode-adjustments') }}">Premium
                                Adjustments
                            </a>
                        </li>
                        <li class="nav-item {{ active_class(['farmer/insurance/product-benefits']) }}">
                            <a class="nav-link" href="{{ route('insurance.product-benefits') }}">Product
                                Benefits</a>
                        </li>
                        <li class="nav-item {{ active_class(['farmer/insurance/products']) }}">
                            <a class="nav-link" href="{{ route('insurance.products') }}">Product
                                Premiums</a>
                        </li>
                        <li class="nav-item {{ active_class(['farmer/insurance/my-valuations']) }}">
                            <a class="nav-link" href="{{ route('insurance.valuations') }}">Valuations</a>
                        </li>
                        <li class="nav-item {{ active_class(['farmer/insurance/subscriptions']) }}">
                            <a class="nav-link" href="{{ route('insurance.subscriptions') }}">Insurance
                                Subscriptions</a>
                        </li>
                        <li class="nav-item {{ active_class(['farmer/insurance/products-limit']) }}">
                            <a class="nav-link" href="{{ route('insurance.products-limit') }}">Product
                                Limit</a>
                        </li>
                        <li class="nav-item {{ active_class(['farmer/insurance/claims']) }}">
                            <a class="nav-link" href="{{ route('insurance.claims') }}">Claims</a>
                        </li>
                        <li class="nav-item {{ active_class(['farmer/insurance/transaction-history']) }}">
                            <a class="nav-link" href="{{ route('insurance.transaction-history') }}">Report</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif

        {{--    vet menu --}}
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
                            <a class="nav-link"
                               href="{{ route('vet.my-bookings.show') }}">Bookings</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif

        @if(env('APP_ENV') == 'local' || env('APP_ENV') == 'development')
            <li class="nav-item {!!  active_class(['basic-ui/*']) !!} ">
                <a class="nav-link" data-toggle="collapse" href="#basic-ui"
                   aria-expanded="{!!  is_active_route(['basic-ui/*'])  !!}"
                   aria-controls="basic-ui">
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
                            <a class="nav-link"
                               href="{{ url('/basic-ui/dropdowns') }}">Dropdowns</a>
                        </li>
                        <li class="nav-item {{ active_class(['basic-ui/typography']) }}">
                            <a class="nav-link"
                               href="{{ url('/basic-ui/typography') }}">Typography</a>
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
                   aria-expanded="{{ is_active_route(['user-pages/*']) }}"
                   aria-controls="user-pages">
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
                            <a class="nav-link"
                               href="{{ url('/user-pages/register') }}">Register</a>
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
</nav>
