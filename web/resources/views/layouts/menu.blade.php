@can('admin.dashboard')
    <li class="side-menus {{ Request::is('admin') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
        </a>
    </li>
@endcan
@can('admin.drivers.index')
    <li class="side-menus {{ Request::is('admin/drivers*') ? 'active' : '' }}">
        <a class="nav-link " href="{!! route('admin.drivers.index') !!}"><i class="fas fa-car"></i>
            <span>{{ trans('general.driver_plural') }}</span></a>
    </li>
@endcan
@can('admin.rides.index')
    <li class="side-menus {{ Request::is('admin/rides*') ? 'active' : '' }}">
        <a class="nav-link" href="{!! route('admin.rides.index') !!}"><i class="fas fa-shopping-bag"></i>
            <span>{{ trans('general.ride_plural') }}</span></a>
    </li>
@endcan
@can('admin.users.index')
    <li class="side-menus {{ Request::is('admin/users*') ? 'active' : '' }}">
        <a class="nav-link" href="{!! route('admin.users.index') !!}"><i class="fas fa-users"></i>
            <span>{{ trans('general.user_plural') }}</span></a>
    </li>
@endcan
@can('admin.vehicle_types.index')
    <li class="side-menus {{ Request::is('admin/vehicle_types*') ? 'active' : '' }}">
        <a class="nav-link" href="{!! route('admin.vehicle_types.index') !!}"><i class="fas fa-truck"></i>
            <span>{{ trans('general.vehicle_type_plural') }}</span></a>
    </li>
@endcan
@can('admin.driverPayouts.index')
    <li class="side-menus {{ Request::is('admin/driverPayouts*') ? 'active' : '' }}">
        <a class="nav-link" href="{!! route('admin.driverPayouts.index') !!}"><i class="fas fa-money-bill-alt"></i>
            <span>{{ trans('Drivers Payouts') }}</span></a>
    </li>
@endcan
@if (auth()->user()->can('admin.reports.ridesByDate') ||
    auth()->user()->can('admin.reports.ridesByDriver') ||
    auth()->user()->can('admin.reports.ridesByCustomer'))
    <li class="menu-header">{{ __('general.reports_plural') }}</li>
    @can('admin.reports.ridesByDate')
        <li class="side-menus {{ Request::is('admin/reports/ridesByDate') ? 'active' : '' }}">
            <a class="nav-link" href="{!! route('admin.reports.ridesByDate') !!}"><i class="fas fa-calendar-alt"></i>
                <span>{{ trans('Rides by Date') }}</span></a>
        </li>
    @endcan
    @can('admin.reports.ridesByDriver')
        <li class="side-menus {{ Request::is('admin/reports/ridesByDriver') ? 'active' : '' }}">
            <a class="nav-link" href="{!! route('admin.reports.ridesByDriver') !!}"><i class="fas fa-list"></i>
                <span>{{ trans('Rides by Driver') }}</span></a>
        </li>
    @endcan
    @can('admin.reports.ridesByCustomer')
        <li class="side-menus {{ Request::is('admin/reports/ridesByCustomer') ? 'active' : '' }}">
            <a class="nav-link" href="{!! route('admin.reports.ridesByCustomer') !!}"><i class="fas fa-user-clock"></i>
                <span>{{ trans('Rides by Customer') }}</span></a>
        </li>
    @endcan
@endif
@if (auth()->user()->can('admin.settings.general') ||
    auth()->user()->can('admin.settings.app') ||
    auth()->user()->can('admin.settings.social_login') ||
    auth()->user()->can('admin.settings.payments_api') ||
    auth()->user()->can('admin.settings.notifications') ||
    auth()->user()->can('admin.roles.index') ||
    auth()->user()->can('admin.permissions.index') ||
    auth()->user()->can('admin.settings.currency') ||
    auth()->user()->can('admin.offlinePaymentMethods.index') ||
    auth()->user()->can('admin.settings.legal'))
    <li class="menu-header">{{ __('general.setting_plural') }}</li>
    @can('admin.settings.general')
        <li class="side-menus {{ Request::is('admin/settings/general') ? 'active' : '' }}">
            <a href="{!! route('admin.settings.general') !!}" class="nav-link"><i class="fas fa-cog"></i> <span>@lang('General Settings')</span></a>
        </li>
    @endcan
    @can('admin.settings.app')
        <li class="side-menus {{ Request::is('admin/settings/app') ? 'active' : '' }}">
            <a href="{!! route('admin.settings.app') !!}" class="nav-link"><i class="fas fa-mobile"></i>
                <span>@lang('App Settings')</span></a>
        </li>
    @endcan
    @can('admin.settings.social_login')
        <li class="side-menus {{ Request::is('admin/settings/social_login') ? 'active' : '' }}">
            <a href="{!! route('admin.settings.social_login') !!}" class="nav-link"><i class="fas fa-share-alt"></i>
                <span>@lang('Social Login')</span></a>
        </li>
    @endcan
    @can('admin.settings.payments_api')
        <li class="side-menus {{ Request::is('admin/settings/payments_api') ? 'active' : '' }}">
            <a href="{!! route('admin.settings.payments_api') !!}" class="nav-link"><i class="fas fa-credit-card"></i>
                <span>@lang('Payments API')</span></a>
        </li>
    @endcan
    @can('admin.settings.notifications')
        <li class="side-menus {{ Request::is('admin/settings/notifications') ? 'active' : '' }}">
            <a href="{!! route('admin.settings.notifications') !!}" class="nav-link"><i class="fas fa-envelope"></i>
                <span>@lang('Notifications')</span></a>
        </li>
    @endcan
    @can('admin.roles.index')
        <li class="side-menus {{ Request::is('admin/settings/roles*') ? 'active' : '' }}">
            <a href="{!! route('admin.roles.index') !!}" class="nav-link"><i class="fas fa-lock"></i>
                <span>@lang('Roles')</span></a>
        </li>
    @endcan
    @can('admin.permissions.index')
        <li class="side-menus {{ Request::is('admin/settings/permissions*') ? 'active' : '' }}">
            <a href="{!! route('admin.permissions.index') !!}" class="nav-link"><i class="fas fa-user-lock"></i>
                <span>@lang('Permissions')</span></a>
        </li>
    @endcan
    @can('admin.settings.currency')
        <li class="side-menus {{ Request::is('admin/settings/currency') ? 'active' : '' }}">
            <a href="{!! route('admin.settings.currency') !!}" class="nav-link"><i class="fas fa-coins"></i>
                <span>@lang('Currency')</span></a>
        </li>
    @endcan
    @can('admin.offlinePaymentMethods.index')
        <li class="side-menus {{ Request::is('admin/settings/offlinePaymentMethods*') ? 'active' : '' }}">
            <a href="{!! route('admin.offlinePaymentMethods.index') !!}" class="nav-link"><i class="fas fa-dollar-sign"></i>
                <span>@lang('Offline Payment Methods')</span></a>
        </li>
    @endcan
    @can('admin.settings.legal')
        <li class="side-menus {{ Request::is('admin/settings/legal') ? 'active' : '' }}">
            <a href="{!! route('admin.settings.legal') !!}" class="nav-link"><i class="fas fa-gavel"></i>
                <span>@lang('Legal')</span></a>
        </li>
    @endcan
@endif
