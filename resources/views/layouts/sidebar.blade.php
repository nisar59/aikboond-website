@php
$pref=Request()->route()->getPrefix();
@endphp

      <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="{{url('/dashboard')}}"> <img alt="image" src="{{url('/img/settings/'.Settings()->portal_logo)}}" class="header-logo" /> <span
                class="logo-name"><!-- {{Settings()->portal_name}} --></span>
            </a>
          </div>
          <div class="sidebar-brand sidebar-brand-mini">
            <a href="{{url('/dashboard')}}"> <img alt="image" src="{{url('/img/settings/'.Settings()->portal_favicon)}}" class="header-logo" /> <span
                class="logo-name"><!-- {{Settings()->portal_name}} --></span>
            </a>
          </div>
          <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <li class="dropdown @if($pref=='') active @endif">
              <a href="{{url('/dashboard')}}" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
           
<!--             <li class="dropdown @if($pref=='/tokens') active @endif">
              <a href="{{url('/tokens')}}" class="nav-link"><i class="fas fa-user-friends"></i><span>Your Tokens</span></a>
            </li> -->

<!--             <li class="dropdown @if($pref=='/donors') active @endif">
              <a href="{{url('/donors')}}" class="nav-link"><i class="fas fa-user-friends"></i><span>Search Donors</span></a>
            </li> -->

            <li class="dropdown @if($pref=='/requests') active @endif">
              <a href="{{url('/requests')}}" class="nav-link"><i class="fas fa-scroll"></i><span>Your Requests</span></a>
            </li>
           
          </ul>
        </aside>
      </div>