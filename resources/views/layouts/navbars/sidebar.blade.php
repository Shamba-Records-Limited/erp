<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <a class="navbar-brand pt-0" href="{{ route('home') }}">
            <img src="{{ asset('argon') }}/img/brand/coffee.png" class="navbar-brand-img" alt="...">
        </a>
        <!-- User -->
        <ul class="nav align-items-center d-md-none">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                        <img alt="Image placeholder" src="{{ asset('argon') }}/img/theme/team-1-800x800.jpg">
                        </span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>{{ __('My profile') }}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                </div>
            </li>
        </ul>
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Collapse header -->
            <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="{{ route('home') }}">
                            <!-- <img src="{{ asset('argon') }}/img/brand/blue.png"> -->
                            <img src="{{ asset('argon') }}/img/brand/coffee.png" class="navbar-brand-img" alt="...">
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Form -->
            <!-- <form class="mt-4 mb-3 d-md-none">
                <div class="input-group input-group-rounded input-group-merge">
                    <input type="search" class="form-control form-control-rounded form-control-prepended" placeholder="{{ __('Search') }}" aria-label="Search">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="fa fa-search"></span>
                        </div>
                    </div>
                </div>
            </form> -->
            <!-- Navigation -->
            <ul class="navbar-nav">
            <li class="nav-item {{ active_class(['home*']) }}">
                <a class="nav-link text-black" href="{{ route('home') }}">
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
                <a class="nav-link" href="#navbar-marketplace" data-toggle="collapse" role="button" aria-expanded="{!! is_active_route(['miller-admin/market-auction/*']) !!}" aria-controls="navbar-marketplace">
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
                <a class="nav-link" href="#navbar-inventory" data-toggle="collapse" role="button" aria-expanded="{!! is_active_route(['miller-admin/inventory/*']) !!}" aria-controls="navbar-inventory">
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
                <a class="nav-link" href="#navbar-auction" data-toggle="collapse" role="button" aria-expanded="{!! is_active_route(['miller-admin/inventory-auction*']) !!}" aria-controls="navbar-auction">
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
                <a class="nav-link" href="#navbar-wallet" data-toggle="collapse" role="button" aria-expanded="{!!  is_active_route(['miller-admin/wallet-management/*'])  !!}" aria-controls="navbar-wallet">
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
                            <a class="nav-link" href="{{ route('miller-admin.wallet-management.account-receivables') }}">
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
        </div>
    </div>
</nav>