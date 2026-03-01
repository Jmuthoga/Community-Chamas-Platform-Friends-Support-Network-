@php
$route = request()->route()->getName();
@endphp
<div class="sidebar">
    <!-- Sidebar user panel (optional) -->

    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="{{ auth()->user()->profile_image 
                    ? asset('storage/' . auth()->user()->profile_image) 
                    : asset('assets/images/no-image.png') }}"
                class="img-circle elevation-2"
                style="width: 2.5rem; height: 2.5rem;"
                alt="User Image">
        </div>
        <div class="info">
            <a href="{{ route('backend.admin.profile') }}" class="d-block text-white">
                {{ auth()->user()->name }}
            </a>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            @can('dashboard_view')
            <li class="nav-item">
                <a href="{{ route('backend.admin.dashboard') }}"
                    class="nav-link {{ $route === 'backend.admin.dashboard' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        Dashboard
                    </p>
                </a>
            </li>
            <li class="nav-header text-white">MEMBERS MANAGEMENT</li>
            @if (auth()->user()->hasAnyPermission([
                    //user
                    'user_create',
                    'user_view',
                    'user_update',
                    'user_delete',
                    'user_suspend',
                    ]))
                    <li class="nav-item">
                        <a href="{{ route('backend.admin.users') }}"
                            class="nav-link {{ $route === 'backend.admin.users' ? 'active' : '' }}">
                            <i class="fas fa-circle nav-icon"></i>
                            <p>Members</p>
                        </a>
                    </li>
                    @endif
            
            @endcan

            {{-- ======================== CONTRIBUTIONS ======================== --}}
            @php
                $route = request()->route()->getName();
                $userId = auth()->id(); // current logged-in user
            @endphp

            @if (auth()->user()->hasAnyPermission([
                'view-contribution-view',
                'make-contribution-payment',
                'view-contribution-payment-history',
            ]))
            <li class="nav-header text-white">CONTRIBUTIONS</li>

            <li class="nav-item">
                <a href="#" class="nav-link d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fas fa-hand-holding-usd nav-icon"></i>
                        Monthly Contributions
                    </span>
                    <span class="d-flex justify-content-between align-items-center">
                        <i class="fas fa-angle-left right"></i>
                    </span>
                </a>

                <ul class="nav nav-treeview">
                    @can('contribution_agreement')
                     <li class="nav-item">
                        <a href="{{ route('backend.admin.contributions.settings.view') }}"
                            class="nav-link {{ $route === 'backend.admin.contributions.settings.view' ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Contribution Agreement</p>
                        </a>
                    </li>
                    @endcan
                    @can('view-contribution-view')
                    <li class="nav-item">
                        <a href="{{ route('backend.admin.contributions.index') }}"
                        class="nav-link {{ $route === 'backend.admin.contributions.index' ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>All Contributions</p>
                        </a>
                    </li>
                    @endcan

                    @can('make-contribution-payment')
                    <li class="nav-item">
                        <a href="{{ route('backend.admin.contributions.payments.create') }}"
                        class="nav-link {{ $route === 'backend.admin.contributions.payments.create' ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Make Payment</p>
                        </a>
                    </li>
                    @endcan

                    @can('view-contribution-payment-history')
                    <li class="nav-item">
                        <a href="{{ route('backend.admin.contributions.payments.index') }}"
                        class="nav-link {{ $route === 'backend.admin.contributions.payments.index' ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>My Payment History</p>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endif
            
            {{-- ======================== TREASURER DESK ======================== --}}
            @if (auth()->user()->hasAnyPermission([
                'treasurer_dashboard',
                'treasurer_transactions',
                'treasurer_expenses',
                'treasurer_reports',
            ]))
            <li class="nav-header text-white">TREASURER DESK</li>
            
            <li class="nav-item">
                <a href="#" class="nav-link d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fas fa-wallet nav-icon"></i>
                        Treasurer Desk
                    </span>
                    <i class="fas fa-angle-left right"></i>
                </a>
            
                <ul class="nav nav-treeview">
            
                    @can('treasurer_dashboard')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Financial Overview</p>
                        </a>
                    </li>
                    @endcan
            
                    @can('treasurer_transactions')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>All Transactions</p>
                        </a>
                    </li>
                    @endcan
            
                    @can('treasurer_expenses')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Expenses</p>
                        </a>
                    </li>
                    @endcan
            
                    @can('treasurer_reports')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Financial Reports</p>
                        </a>
                    </li>
                    @endcan
            
                </ul>
            </li>
            @endif
            
            {{-- ======================== ANNOUNCEMENTS ======================== --}}
            @if (auth()->user()->hasAnyPermission([
                'announcement_view',
                'announcement_create',
                'event_view',
                'event_create',
            ]))
            <li class="nav-header text-white">ANNOUNCEMENTS</li>
            
            <li class="nav-item">
                <a href="#" class="nav-link d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fas fa-bullhorn nav-icon"></i>
                        Announcements
                    </span>
                    <i class="fas fa-angle-left right"></i>
                </a>
            
                <ul class="nav nav-treeview">
            
                    @can('announcement_view')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>All Announcements</p>
                        </a>
                    </li>
                    @endcan
            
                    @can('announcement_create')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Create Announcement</p>
                        </a>
                    </li>
                    @endcan
            
                    @can('event_view')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-calendar-alt nav-icon"></i>
                            <p>Events</p>
                        </a>
                    </li>
                    @endcan
            
                    @can('event_create')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-calendar-plus nav-icon"></i>
                            <p>Create Event</p>
                        </a>
                    </li>
                    @endcan
            
                </ul>
            </li>
            @endif
            
            {{-- ======================== LOANS MANAGEMENT ======================== --}}
            @if (auth()->user()->hasAnyPermission([
                'loan_apply',
                'loan_view',
                'loan_approve',
                'loan_repayment',
                'loan_settings',
                'loan_calculate',
            ]))
            <li class="nav-header text-white">LOANS</li>
            
            <li class="nav-item">
                <a href="#" class="nav-link d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fas fa-hand-holding nav-icon"></i>
                        Loans Management
                    </span>
                    <i class="fas fa-angle-left right"></i>
                </a>
            
                <ul class="nav nav-treeview">
            
                    {{-- Apply Loan --}}
                    @can('loan_apply')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Apply for Loan</p>
                        </a>
                    </li>
                    @endcan
            
                    {{-- My Loans --}}
                    @can('loan_view')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>My Loans</p>
                        </a>
                    </li>
                    @endcan
            
                    {{-- Loan Approvals --}}
                    @can('loan_approve')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-check-circle nav-icon"></i>
                            <p>Loan Approvals</p>
                        </a>
                    </li>
                    @endcan
            
                    {{-- Loan Repayments --}}
                    @can('loan_repayment')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-money-bill-alt nav-icon"></i>
                            <p>Loan Repayments</p>
                        </a>
                    </li>
                    @endcan
            
                    {{-- Loan Calculator --}}
                    @can('loan_calculate')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-calculator nav-icon"></i>
                            <p>Loan Calculator</p>
                        </a>
                    </li>
                    @endcan
            
                    {{-- Loan Settings --}}
                    @can('loan_settings')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-cogs nav-icon"></i>
                            <p>Loan Settings</p>
                        </a>
                    </li>
                    @endcan
            
                </ul>
            </li>
            @endif
            
            {{-- ======================== REPORTS ======================== --}}
            @if (auth()->user()->hasAnyPermission([
                'report_members',
                'report_contributions',
                'report_contribution_payments',
                'report_financial',
                'report_expenses',
                'report_transactions',
                'report_loans',
                'report_loan_repayments',
                'report_announcements',
                'report_events',
                'report_summary',
            ]))
            <li class="nav-header text-white">REPORTS</li>
            
            <li class="nav-item">
                <a href="#" class="nav-link d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fas fa-chart-line nav-icon"></i>
                        Reports
                    </span>
                    <i class="fas fa-angle-left right"></i>
                </a>
            
                <ul class="nav nav-treeview">
            
                    {{-- General Summary --}}
                    @can('report_summary')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-chart-pie nav-icon"></i>
                            <p>System Summary</p>
                        </a>
                    </li>
                    @endcan
            
                    {{-- Members Reports --}}
                    @can('report_members')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-users nav-icon"></i>
                            <p>Members Reports</p>
                        </a>
                    </li>
                    @endcan
            
                    {{-- Contribution Reports --}}
                    @can('report_contributions')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-hand-holding-usd nav-icon"></i>
                            <p>Contribution Reports</p>
                        </a>
                    </li>
                    @endcan
            
                    {{-- Contribution Payments --}}
                    @can('report_contribution_payments')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-receipt nav-icon"></i>
                            <p>Contribution Payments</p>
                        </a>
                    </li>
                    @endcan
            
                    {{-- Financial Reports --}}
                    @can('report_financial')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-wallet nav-icon"></i>
                            <p>Financial Reports</p>
                        </a>
                    </li>
                    @endcan
            
                    {{-- Expenses Reports --}}
                    @can('report_expenses')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-money-bill-wave nav-icon"></i>
                            <p>Expenses Reports</p>
                        </a>
                    </li>
                    @endcan
            
                    {{-- Transactions Reports --}}
                    @can('report_transactions')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-exchange-alt nav-icon"></i>
                            <p>Transactions Reports</p>
                        </a>
                    </li>
                    @endcan
            
                    {{-- Loans Reports --}}
                    @can('report_loans')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-hand-holding nav-icon"></i>
                            <p>Loan Reports</p>
                        </a>
                    </li>
                    @endcan
            
                    {{-- Loan Repayment Reports --}}
                    @can('report_loan_repayments')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-money-check-alt nav-icon"></i>
                            <p>Loan Repayment Reports</p>
                        </a>
                    </li>
                    @endcan
            
                    {{-- Announcement Reports --}}
                    @can('report_announcements')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-bullhorn nav-icon"></i>
                            <p>Announcement Reports</p>
                        </a>
                    </li>
                    @endcan
            
                    {{-- Event Reports --}}
                    @can('report_events')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-calendar nav-icon"></i>
                            <p>Event Reports</p>
                        </a>
                    </li>
                    @endcan
            
                </ul>
            </li>
            @endif

            {{-- settings --}}
            @if (auth()->user()->hasAnyPermission([
            //currency
            'currency-create',
            'currency-view',
            'currency-update',
            'currency-delete',
            'currency-set-default',
            //role
            'role_create',
            'role_view',
            'role_update',
            'role_delete',
            'permission_view',
            //user
            'user_create',
            'user_view',
            'user_update',
            'user_delete',
            'user_suspend',
            //setting
            'website_settings',
            'contact_settings',
            'socials_settings',
            'style_settings',
            'custom_settings',
            'notification_settings',
            'website_status_settings',
            'invoice_settings',
            ]))
            <li class="nav-header text-white">SETTINGS</li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-cog nav-icon"></i>
                    <p>
                        Website Settings
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    {{-- ======================== CONTRIBUTION SETTINGS ======================== --}}
                    @can('website_settings') 
                    <li class="nav-item">
                        <a href="#" class="nav-link d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fas fa-cogs nav-icon"></i>
                                Contributions
                            </span>
                            <span>
                                <i class="fas fa-angle-left right"></i>
                            </span>
                        </a>

                        <ul class="nav nav-treeview">
                            {{-- Edit / update settings --}}
                            <li class="nav-item">
                                <a href="{{ route('backend.admin.contributions.settings') }}"
                                    class="nav-link {{ $route === 'backend.admin.contributions.settings' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Edit Settings</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endcan
                    @if (auth()->user()->hasAnyPermission(['currency-create','currency-view','currency-update','currency-delete']))
                    <li class="nav-item">
                        <a href="{{ route('backend.admin.currencies.index') }}"
                            class="nav-link {{ request()->routeIs([ 'backend.admin.currencies.index', 'backend.admin.currencies.create', 'backend.admin.currencies.edit']) ? 'active' : '' }}">
                            <i class="fas fa-coins nav-icon"></i>
                            <p>Currency</p>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->hasAnyPermission([
                    'role_create',
                    'role_view',
                    'role_update',
                    'role_delete',
                    'permission_view',
                    ]))
                    <li class="nav-item">
                        <a href="#" class="nav-link d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fas fa-chevron-circle-right nav-icon"></i>
                                Roles & Permissions
                            </span>
                            <span class="d-flex justify-content-between align-items-center">
                                <i class="fas fa-angle-left right"></i>
                            </span>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('role_view')
                            <li class="nav-item">
                                <a href="{{ route('backend.admin.roles') }}"
                                    class="nav-link {{ $route === 'backend.admin.roles' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Roles</p>
                                </a>
                            </li>
                            @endcan
                            @can('permission_view')
                            <li class="nav-item">
                                <a href="{{ route('backend.admin.permissions') }}"
                                    class="nav-link {{ $route === 'backend.admin.permissions' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Permissions</p>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endif
                    
                    @if (auth()->user()->hasAnyPermission([
                    'website_settings',
                    'contact_settings',
                    'socials_settings',
                    'style_settings',
                    'custom_settings',
                    'notification_settings',
                    'website_status_settings',
                    'invoice_settings',
                    ]))
                    <li class="nav-item">
                        <a href="{{ route('backend.admin.settings.website.general') }}?active-tab=website-info"
                            class="nav-link {{ $route === 'backend.admin.settings.website.general' ? 'active' : '' }}">
                            <i class="fas fa-circle nav-icon"></i>
                            <p>General Settings</p>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>

<script>
    // Get all elements with the nav-treeview class
    const treeviewElements = document.querySelectorAll('.nav-treeview');

    // Iterate over each treeview element
    treeviewElements.forEach(treeviewElement => {
        // Check if it has the nav-link and active classes
        const navLinkElements = treeviewElement.querySelectorAll('.nav-link.active');

        // If there are nav-link elements with the active class, log the treeview element
        if (navLinkElements.length > 0) {
            // Add the menu-open class to the parent nav-item
            const parentNavItem = treeviewElement.closest('.nav-item');
            if (parentNavItem) {
                parentNavItem.classList.add('menu-open');
            }

            // Add the active class to the immediate child nav-link
            const childNavLink = parentNavItem.querySelector('.nav-link');
            if (childNavLink) {
                childNavLink.classList.add('active');
            }
        }
    });
</script>