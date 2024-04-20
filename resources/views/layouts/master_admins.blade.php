<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title_admin') | DianIstana </title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/template/main/img/dianlogo.png') }}">
    <link href="{{ asset('') }}assets/template/layouts/modern-light-menu/css/light/loader.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/template/layouts/modern-light-menu/css/dark/loader.css" rel="stylesheet" type="text/css" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="{{ asset('') }}assets/template/src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/template/layouts/modern-light-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/template/layouts/modern-light-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/template/src/plugins/src/table/datatable/datatables.css">
    
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/template/src/plugins/css/light/table/datatable/dt-global_style.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/template/src/plugins/css/dark/table/datatable/dt-global_style.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/template/src/plugins/css/light/table/datatable/custom_dt_miscellaneous.css">

    <link rel="stylesheet" href="{{ asset('') }}assets/template/src/plugins/src/font-icons/fontawesome-5.15.4/css/all.css">

    <link rel="stylesheet" href="{{ asset('') }}assets/template/src/plugins/src/sweetalerts2/sweetalerts2.css">
    <link href="{{ asset('') }}assets/template/src/plugins/css/light/sweetalerts2/custom-sweetalert.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/template/src/plugins/css/dark/sweetalerts2/custom-sweetalert.css" rel="stylesheet" type="text/css" />

    <link href="{{ asset('') }}assets/template/src/assets/css/light/scrollspyNav.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/template/src/assets/css/light/components/carousel.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('') }}assets/template/src/assets/css/light/components/modal.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/template/src/assets/css/light/components/tabs.css" rel="stylesheet" type="text/css">
    
    <link href="{{ asset('') }}assets/template/src/assets/css/dark/scrollspyNav.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/template/src/assets/css/dark/components/carousel.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('') }}assets/template/src/assets/css/dark/components/modal.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/template/src/assets/css/dark/components/tabs.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL STYLES -->
    @yield("level_styles_admin")
    <!-- END PAGE LEVEL STYLES -->

    @include('css');
</head>
<body class="layout-boxed">
    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    <div class="header-container container-xxl">
        <header class="header navbar navbar-expand-sm expand-header">

            <a href="javascript:void(0);" class="sidebarCollapse">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
            </a>

            {{-- <div class="search-animated toggle-search">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <form class="form-inline search-full form-inline search" role="search">
                    <div class="search-bar">
                        <input type="text" class="form-control search-form-control  ml-lg-auto" placeholder="Search...">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x search-close"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </div>
                </form>
                <span class="badge badge-secondary">Ctrl + /</span>
            </div> --}}

            <ul class="navbar-item flex-row ms-lg-auto ms-0">

                <li class="nav-item theme-toggle-item">
                    <a href="javascript:void(0);" class="nav-link theme-toggle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-moon dark-mode"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sun light-mode"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                    </a>
                </li>

               
                <li class="nav-item dropdown notification-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle show" id="notificationDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg><span id="admin-notif-number" class="badge badge-danger notif-badge-number">
                            {{ session('session_number_admin') == null ? 0 : session('session_number_admin')}}
                        </span>
                    </a>                    
                </li>
                <li class="nav-item dropdown user-profile-dropdown  order-lg-0 order-1">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="avatar-container">
                            <div class="avatar avatar-sm avatar-indicators avatar-online">
                                <img alt="avatar" src="{{ adminAuth()->avatar_src }}" class="rounded-circle">
                                
                            </div>
                        </div>
                    </a>

                    <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                        <div class="user-profile-section">
                            <div class="media mx-auto">
                                <div class="media-body">
                                    <h5>{{ adminAuth()->name }}</h5>
                                    <p>{{ adminAuth()->level }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-item">
                            <a href="{{ url('backdata/change_password') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-key"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path></svg> <span>Change Password</span>
                            </a>
                        </div>
                        {{-- <div class="dropdown-item">
                            <a href="auth-boxed-lockscreen.html">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg> <span>Lock Screen</span>
                            </a>
                        </div> --}}
                        <div class="dropdown-item">
                            <a href="{{ route("logout_admin") }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> <span>Log Out</span>
                            </a>
                        </div>
                    </div>
                    
                </li>
            </ul>
        </header>
    </div>
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container " id="container">

        <div class="overlay"></div>
        <div class="cs-overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        <div class="sidebar-wrapper sidebar-theme">

            <nav id="sidebar">

                <div class="navbar-nav theme-brand flex-row  text-center">
                    <div class="nav-logo">
                        {{-- <div class="nav-item theme-logo">
                            <a href="./index.html">
                                <img src="{{ asset('') }}assets/template/src/assets/img/logo.svg" class="navbar-logo" alt="logo">
                            </a>
                        </div> --}}
                        <div class="nav-item theme-text">
                            <a href="{{ route("home_admin") }}" class="nav-link">Dian Istana</a>
                        </div>
                    </div>
                    <div class="nav-item sidebar-toggle">
                        <div class="btn-toggle sidebarCollapse">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>
                        </div>
                    </div>
                </div>
                <div class="profile-info">
                    <div class="user-info">
                        <div class="profile-img">
                            <img src="{{ adminAuth()->avatar_src }}" alt="avatar">
                        </div>
                        <div class="profile-content">
                            <h6>{{ adminAuth()->name }}</h6>
                            <p class="">{{ adminAuth()->level }}</p>
                        </div>
                    </div>
                </div>
                                
                <div class="shadow-bottom"></div>
                <ul class="list-unstyled menu-categories" id="accordionExample">
                    <li class="menu {{ requestIsActive('backdata') }}">
                        <a href="{{ route("home_admin") }}" aria-expanded="false" class="dropdown-toggle">
                            <div class="icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                <span>Dashboard</span>
                            </div>
                        </a>
                    </li>

                    <li class="menu menu-heading">
                        <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span>Master Data</span></div>
                    </li>
                    <li class="menu {{ requestIsActive('backdata/unit-bisnis') }}">
                        <a href="{{ url('backdata/unit-bisnis') }}" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layout"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                                <span>Unit Bisnis</span>
                            </div>
                        </a>
                    </li>
                    <li class="menu {{ requestIsActive('backdata/banner-iklan') }}">
                        <a href="{{ url('backdata/banner-iklan') }}" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layout"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                                <span>Banner Iklan</span>
                            </div>
                        </a>
                    </li>
                    
                    <li class="menu {{ requestIsActive('backdata/user') }}">
                        <a href="{{ url('backdata/user') }}" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layout"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                                <span>Data User</span>
                            </div>
                        </a>
                    </li>

                    <li class="menu menu-heading">
                        <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span>Transaction</span></div>
                    </li>

                    <li class="menu {{ requestIsActive('backdata/transaction') }}">
                        <a href="{{ url('backdata/transaction') }}" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layout"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                                <span>Booking</span>
                            </div>
                        </a>
                    </li>

                    <li class="menu {{ requestIsActive('backdata/ticketing') }}">
                        <a href="{{ url('backdata/ticketing') }}" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layout"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                                <span>Ticketing</span>
                            </div>
                        </a>
                    </li>

                    <li class="menu {{ requestIsActive('backdata/pembayaran') }}">
                        <a href="{{ url('backdata/pembayaran') }}" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layout"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                                <span>Payment</span>
                            </div>
                        </a>
                    </li>
                    
                    <li class="menu menu-heading">
                        <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span>Utility</span></div>
                    </li>

                    <li class="menu {{ requestIsActive('backdata/broadcasting') }}">
                        <a href="{{ url('backdata/broadcasting') }}" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layout"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                                <span>Broadcasting</span>
                            </div>
                        </a>
                    </li>

                    <li class="menu menu-heading">
                        <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span>Laporan</span></div>
                    </li>

                    <li class="menu {{ requestIsActive('backdata/report-iuran') }}">
                        <a href="{{ url('backdata/report-iuran') }}" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layout"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                                <span>Laporan Iuran</span>
                            </div>
                        </a>
                    </li>

                    <li class="menu {{ requestIsActive('backdata/report-unit') }}">
                        <a href="{{ url('backdata/report-unit') }}" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layout"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                                <span>Laporan Unit Bisnis</span>
                            </div>
                        </a>
                    </li>
                    <li class="menu {{ requestIsActive('backdata/report-lain') }}">
                        <a href="{{ url('backdata/report-lain') }}" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layout"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                                <span>Laporan Pendapatan Lain Lain</span>
                            </div>
                        </a>
                    </li>

                    <li class="menu menu-heading">
                        <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span>Data Pengguna</span></div>
                    </li>

                    <li class="menu {{ requestIsActive('backdata/admins') }}">
                        <a href="{{ url('backdata/admins') }}" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layout"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                                <span>Data Admin</span>
                            </div>
                        </a>
                    </li>
                    <li class="menu menu-heading">
                        <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span>Setting</span></div>
                    </li>

                    <li class="menu {{ requestIsActive('backdata/setting') }}">
                        <a href="{{ url('backdata/setting') }}" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layout"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                                <span>App Settting</span>
                            </div>
                        </a>
                    </li>
                    <li style="margin-bottom: 80px;" class="menu {{ requestIsActive('backdata/booking_setting') }}">
                        <a href="{{ url('backdata/booking_setting') }}" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layout"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                                <span>Booking Settting</span>
                            </div>
                        </a>
                    </li>
                   

                    
                </ul>
                
            </nav>

        </div>
        <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="middle-content container-xxl p-0">
                    <!-- BREADCRUMB -->
                    <div class="page-meta">
                        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                @yield("breadcrumb_admin")
                            </ol>
                        </nav>
                    </div>
                    <!-- /BREADCRUMB -->
    
                    <div class="row layout-top-spacing">
                        @yield("content_admin")
                    </div>
                </div>
            </div>
            
            <!--  BEGIN FOOTER  -->
            <div class="footer-wrapper mt-0">
                <div class="footer-section f-section-1">
                    <p class="">Copyright © <span class="dynamic-year">2022</span> <a target="_blank" href="https://designreset.com/cork-admin/">DesignReset</a>, All rights reserved.</p>
                </div>
                <div class="footer-section f-section-2">
                    <p class="">Coded with <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></p>
                </div>
            </div>
            <!--  END CONTENT AREA  -->
        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->
    <audio id="notification" src="{{ asset('sound/sound_notif.mp3') }}" muted></audio>

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('') }}assets/template/src/plugins/src/global/vendors.min.js"></script>
    <script>
        var url_asset = "{{ asset('') }}";
        var url_ori = '{{ url("") }}';
        var url_back = '{{ url("backdata") }}';
        var tokenCsrf = $('meta[name=csrf-token]').attr('content');
        var assetImg_thumbnail = "{{ assetImg_thumbnail() }}";
    </script>
    <script src="{{ asset('') }}assets/template/layouts/modern-light-menu/loader.js?vm=9348"></script>
    <script src="{{ asset('') }}assets/template/src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('') }}assets/template/src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="{{ asset('') }}assets/template/src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="{{ asset('') }}assets/template/src/plugins/src/table/datatable/datatables.js"></script>
    
    <script src="{{ asset('') }}assets/template/src/plugins/src/table/datatable/button-ext/dataTables.buttons.min.js"></script>
    <script src="{{ asset('') }}assets/template/src/plugins/src/table/datatable/button-ext/jszip.min.js"></script>    
    <script src="{{ asset('') }}assets/template/src/plugins/src/table/datatable/button-ext/buttons.html5.min.js"></script>
    <script src="{{ asset('') }}assets/template/src/plugins/src/table/datatable/button-ext/buttons.print.min.js"></script>
    <script src="{{ asset('') }}assets/template/src/plugins/src/table/datatable/custom_miscellaneous.js"></script>

    <script src="{{ asset('') }}assets/template/src/plugins/src/waves/waves.min.js"></script>
    <script src="{{ asset('') }}assets/template/layouts/modern-light-menu/app.js?vm=934598"></script>

    <script src="{{ asset('') }}assets/template/src/plugins/src/highlight/highlight.pack.js"></script>
    <script src="{{ asset('') }}assets/template/src/assets/js/custom.js"></script>
    <script src="{{ asset('') }}assets/template/src/plugins/src/sweetalerts2/sweetalerts2.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- <script src="{{ asset('') }}assets/template/src/assets/js/scrollspyNav.js"></script> --}}
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    @yield("level_scripts_admin")
    <!-- END PAGE LEVEL SCRIPTS -->
    
    @yield("script_admin")
    play_notif();
    <script>
        function play_notif() {
            document.getElementById('notification').muted = false;
            document.getElementById('notification').play();
        }
    </script>

   <!-- The core Firebase JS SDK is always required and must be listed first -->
   <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js"></script>
   <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js"></script>        
   <script type="module">
         const firebaseConfig = {
           apiKey: "AIzaSyD4_3G9UpqpWg-Xk7On-PwzaY9bU-wiMl8",
           authDomain: "my-dian-istana.firebaseapp.com",
           projectId: "my-dian-istana",
           storageBucket: "my-dian-istana.appspot.com",
           messagingSenderId: "832093630984",
           appId: "1:832093630984:web:a0d969f7afe0a1a212d7bd"
       };
     

    // Initialize Firebase
        firebase.initializeApp(firebaseConfig);

        const messaging = firebase.messaging();

        function initFirebaseMessagingRegistration() {
            
            messaging.requestPermission().then(function () {
                return messaging.getToken()
            }).then(function(token) {
                $.ajax({
                    url:"{{ url('backdata/save_firebase_token') }}"+"/"+token,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        console.log(token);
                    }
                }) 
                
            }).catch(function (err) {
                console.log(`Token Error :: ${err}`);
            });
        }

        initFirebaseMessagingRegistration();

        messaging.onMessage(function(data){
            
            new Notification(data.notification.title, {data});
            play_notif();
            $.ajax({
                url: "{{ url('backdata/update_notif_number') }}",
                type: "GET",
                success: function(data) {
                    $("#admin-notif-number").text(data);
                }
            })
        });
   </script>

    @include('js')

    
</body>
</html>