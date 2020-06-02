<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Metrica - Responsive Bootstrap 4 Admin Dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A premium admin dashboard template by Mannatthemes" name="description" />
        <meta content="Mannatthemes" name="author" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="../assets/images/favicon.ico">

        <!--Chartist Chart CSS -->
        <link rel="stylesheet" href="../assets/plugins/chartist/css/chartist.min.css">

        <!-- App css -->
        <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/style.css" rel="stylesheet" type="text/css" />

    </head>

    <body>

        <!-- Top Bar Start -->
        <div class="topbar">
            
            <!-- Navbar -->
            <nav class="topbar-main">  
                <!-- LOGO -->
                <div class="topbar-left">
                    <a href="../projects/projects-index.html" class="logo">
                        <span>
                            <img src="../assets/images/logo-sm.png" alt="logo-small" class="logo-sm">
                        </span>
                        <span>
                            <img src="../assets/images/logo-dark.png" alt="logo-large" class="logo-lg">
                        </span>
                    </a>
                </div><!--topbar-left-->
                <!--end logo-->
                <ul class="list-unstyled topbar-nav float-right mb-0"> 
                    <li class="hidden-sm">
                        <div class="crypto-balance">
                            <i class="dripicons-wallet text-muted align-self-center"></i>
                            <div class="btc-balance">                            
                                <h5 class="m-0">9.521.32 <span>BTC</span></h5>
                                <span class="text-muted">Total Balance</span>
                            </div>
                        </div>
                    </li>
                    <li class="hidden-sm">
                        <a class="nav-link dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="javascript: void(0);" role="button"
                            aria-haspopup="false" aria-expanded="false">
                            English <img src="../assets/images/flags/us_flag.jpg" class="ml-2" height="16" alt=""/> <i class="mdi mdi-chevron-down"></i> 
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="javascript: void(0);"><span> German </span><img src="../assets/images/flags/germany_flag.jpg" alt="" class="ml-2 float-right" height="14"/></a>
                            <a class="dropdown-item" href="javascript: void(0);"><span> Italian </span><img src="../assets/images/flags/italy_flag.jpg" alt="" class="ml-2 float-right" height="14"/></a>
                            <a class="dropdown-item" href="javascript: void(0);"><span> French </span><img src="../assets/images/flags/french_flag.jpg" alt="" class="ml-2 float-right" height="14"/></a>
                            <a class="dropdown-item" href="javascript: void(0);"><span> Spanish </span><img src="../assets/images/flags/spain_flag.jpg" alt="" class="ml-2 float-right" height="14"/></a>
                            <a class="dropdown-item" href="javascript: void(0);"><span> Russian </span><img src="../assets/images/flags/russia_flag.jpg" alt="" class="ml-2 float-right" height="14"/></a>
                        </div>
                    </li><!--end li-->

                    <li class="dropdown notification-list">
                        <a class="nav-link dropdown-toggle arrow-none waves-light waves-effect" data-toggle="dropdown" href="#" role="button"
                            aria-haspopup="false" aria-expanded="false">
                            <i class="dripicons-bell noti-icon"></i>
                            <span class="badge badge-danger badge-pill noti-icon-badge">2</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-lg">
                            <!-- item-->
                            <h6 class="dropdown-item-text">
                                Notifications (18)
                            </h6>
                            <div class="slimscroll notification-list">
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item active">
                                    <div class="notify-icon bg-success"><i class="mdi mdi-cart-outline"></i></div>
                                    <p class="notify-details">Your order is placed<small class="text-muted">Dummy text of the printing and typesetting industry.</small></p>
                                </a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-warning"><i class="mdi mdi-message"></i></div>
                                    <p class="notify-details">New Message received<small class="text-muted">You have 87 unread messages</small></p>
                                </a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-info"><i class="mdi mdi-glass-cocktail"></i></div>
                                    <p class="notify-details">Your item is shipped<small class="text-muted">It is a long established fact that a reader will</small></p>
                                </a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-primary"><i class="mdi mdi-cart-outline"></i></div>
                                    <p class="notify-details">Your order is placed<small class="text-muted">Dummy text of the printing and typesetting industry.</small></p>
                                </a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-danger"><i class="mdi mdi-message"></i></div>
                                    <p class="notify-details">New Message received<small class="text-muted">You have 87 unread messages</small></p>
                                </a>
                            </div>
                            <!-- All-->
                            <a href="javascript:void(0);" class="dropdown-item text-center text-primary">
                                View all <i class="fi-arrow-right"></i>
                            </a>
                        </div>
                    </li><!--end notification-list-->

                    <li class="dropdown">
                        <a class="nav-link dropdown-toggle waves-effect waves-light nav-user pr-0" data-toggle="dropdown" href="#" role="button"
                            aria-haspopup="false" aria-expanded="false">
                            <img src="../assets/images/users/user-4.jpg" alt="profile-user" class="rounded-circle" /> 
                            <span class="ml-1 nav-user-name hidden-sm">Amelia <i class="mdi mdi-chevron-down"></i> </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#"><i class="dripicons-user text-muted mr-2"></i> Profile</a>
                            <a class="dropdown-item" href="#"><i class="dripicons-wallet text-muted mr-2"></i> My Wallet</a>
                            <a class="dropdown-item" href="#"><i class="dripicons-gear text-muted mr-2"></i> Settings</a>
                            <a class="dropdown-item" href="#"><i class="dripicons-lock text-muted mr-2"></i> Lock screen</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#"><i class="dripicons-exit text-muted mr-2"></i> Logout</a>
                        </div>
                    </li><!--end dropdown-->
                    <li class="menu-item">
                        <!-- Mobile menu toggle-->
                        <a class="navbar-toggle nav-link" id="mobileToggle">
                            <div class="lines">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </a>
                        <!-- End mobile menu toggle-->
                    </li> <!--end menu item-->   
                </ul><!--end topbar-nav-->
    
                <ul class="list-unstyled topbar-nav mb-0">
                    <li class="hide-phone app-search">
                        <form role="search" class="">
                            <input type="text" placeholder="Search..." class="form-control">
                            <a href=""><i class="fas fa-search"></i></a>
                        </form>
                    </li>
                </ul><!--end topbar-nav-->
            </nav>
            <!-- end navbar-->
             <!-- MENU Start -->
            <div class="navbar-custom-menu">
                <div class="container-fluid">
                    <div id="navigation">
                        <!-- Navigation Menu-->
                        <ul class="navigation-menu">
                            <li class="has-submenu">
                                <a href="#">
                                    <svg class="nav-svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                    viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                        <g>
                                            <path d="M184,448h48c4.4,0,8-3.6,8-8V72c0-4.4-3.6-8-8-8h-48c-4.4,0-8,3.6-8,8v368C176,444.4,179.6,448,184,448z"/>
                                            <path class="svg-primary" d="M88,448H136c4.4,0,8-3.6,8-8V296c0-4.4-3.6-8-8-8H88c-4.4,0-8,3.6-8,8V440C80,444.4,83.6,448,88,448z"/>
                                            <path class="svg-primary" d="M280.1,448h47.8c4.5,0,8.1-3.6,8.1-8.1V232.1c0-4.5-3.6-8.1-8.1-8.1h-47.8c-4.5,0-8.1,3.6-8.1,8.1v207.8
                                                C272,444.4,275.6,448,280.1,448z"/>
                                            <path d="M368,136.1v303.8c0,4.5,3.6,8.1,8.1,8.1h47.8c4.5,0,8.1-3.6,8.1-8.1V136.1c0-4.5-3.6-8.1-8.1-8.1h-47.8
                                                C371.6,128,368,131.6,368,136.1z"/>
                                        </g>
                                    </svg>
                                    <span>Analytics</span>
                                </a>
                                <ul class="submenu">
                                    <li><a href="../analytics/analytics-index.html"><i class="dripicons-meter"></i>Dashboard</a></li>
                                    <li><a href="../analytics/analytics-customers.html"><i class="dripicons-user-group"></i>Customers</a></li>
                                    <li><a href="../analytics/analytics-reports.html"><i class="dripicons-document"></i>Reports</a></li>
                                </ul><!--end submenu-->
                            </li><!--end has-submenu-->

                            <li class="has-submenu">
                                <a href="#">
                                    <svg class="nav-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path class="svg-primary" d="M410.5 279.2c-5-11.5-12.7-21.6-28.1-30.1-8.2-4.5-16.1-7.8-25.4-10 5.4-2.5 10-5.4 16.3-11 7.5-6.6 13.1-15.7 15.6-23.3 2.6-7.5 4.1-18 3.5-28.2-1.1-16.8-4.4-33.1-13.2-44.8-8.8-11.7-21.2-20.7-37.6-27-12.6-4.8-25.5-7.8-45.5-8.9V32h-40v64h-32V32h-41v64H96v48h27.9c8.7 0 14.6.8 17.6 2.3 3.1 1.5 5.3 3.5 6.5 6 1.3 2.5 1.9 8.4 1.9 17.5V343c0 9-.6 14.8-1.9 17.4-1.3 2.6-2 4.9-5.1 6.3-3.1 1.4-3.2 1.3-11.8 1.3h-26.4L96 416h87v64h41v-64h32v64h40v-64.4c26-1.3 44.5-4.7 59.4-10.3 19.3-7.2 34.1-17.7 44.7-31.5 10.6-13.8 14.9-34.9 15.8-51.2.7-14.5-.9-33.2-5.4-43.4zM224 150h32v74h-32v-74zm0 212v-90h32v90h-32zm72-208.1c6 2.5 9.9 7.5 13.8 12.7 4.3 5.7 6.5 13.3 6.5 21.4 0 7.8-2.9 14.5-7.5 20.5-3.8 4.9-6.8 8.3-12.8 11.1v-65.7zm28.8 186.7c-7.8 6.9-12.3 10.1-22.1 13.8-2 .8-4.7 1.4-6.7 1.9v-82.8c5 .8 7.6 1.8 11.3 3.4 7.8 3.3 15.2 6.9 19.8 13.2 4.6 6.3 8 15.6 8 24.7 0 10.9-2.8 19.2-10.3 25.8z"/>
                                    </svg>
                                    <span>Crypto</span>
                                </a>
                                <ul class="submenu">
                                    <li><a href="../crypto/crypto-index.html"><i class="dripicons-device-desktop"></i>Dashboard</a></li>
                                    <li><a href="../crypto/crypto-exchange.html"><i class="dripicons-swap"></i>Exchange</a></li>
                                    <li><a href="../crypto/crypto-wallet.html"><i class="dripicons-wallet"></i>My Wallet</a></li>
                                    <li><a href="../crypto/crypto-calendar.html"><i class="dripicons-calendar"></i>Calendar</a></li>
                                    <li><a href="../crypto/crypto-news.html"><i class="dripicons-blog"></i>Crypto News</a></li>
                                    <li><a href="../crypto/crypto-ico.html"><i class="dripicons-stack"></i>ICO List</a></li>
                                    <li><a href="../crypto/crypto-settings.html"><i class="dripicons-gear"></i>Settings</a></li>
                                </ul><!--end submenu-->
                            </li><!--end has-submenu-->

                            <li class="has-submenu">
                                <a href="#">
                                    <svg class="nav-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path class="svg-primary" d="M256 32C132.288 32 32 132.288 32 256s100.288 224 224 224 224-100.288 224-224S379.712 32 256 32zm135.765 359.765C355.5 428.028 307.285 448 256 448s-99.5-19.972-135.765-56.235C83.972 355.5 64 307.285 64 256s19.972-99.5 56.235-135.765C156.5 83.972 204.715 64 256 64s99.5 19.972 135.765 56.235C428.028 156.5 448 204.715 448 256s-19.972 99.5-56.235 135.765z"/>
                                        <path d="M200.043 106.067c-40.631 15.171-73.434 46.382-90.717 85.933H256l-55.957-85.933zM412.797 288A160.723 160.723 0 0 0 416 256c0-36.624-12.314-70.367-33.016-97.334L311 288h101.797zM359.973 134.395C332.007 110.461 295.694 96 256 96c-7.966 0-15.794.591-23.448 1.715L310.852 224l49.121-89.605zM99.204 224A160.65 160.65 0 0 0 96 256c0 36.639 12.324 70.394 33.041 97.366L201 224H99.204zM311.959 405.932c40.631-15.171 73.433-46.382 90.715-85.932H256l55.959 85.932zM152.046 377.621C180.009 401.545 216.314 416 256 416c7.969 0 15.799-.592 23.456-1.716L201.164 288l-49.118 89.621z"/>
                                    </svg>
                                    <span>Projects</span>
                                </a>
                                <ul class="submenu">
                                    <li><a href="../projects/projects-index.html"><i class="dripicons-view-thumb"></i>Dashboard</a></li>
                                    <li><a href="../projects/projects-clients.html"><i class="dripicons-user-id"></i>Clients</a></li>
                                    <li><a href="../projects/projects-calendar.html"><i class="dripicons-calendar"></i>Calendar</a></li>
                                    <li><a href="../projects/projects-team.html"><i class="dripicons-trophy"></i>Team</a></li>
                                    <li><a href="../projects/projects-project.html"><i class="dripicons-jewel"></i>Project</a></li>
                                    <li><a href="../projects/projects-task.html"><i class="dripicons-checklist"></i>Tasks</a></li>
                                    <li><a href="../projects/projects-kanban-board.html"><i class="dripicons-move"></i>Kanban Board</a></li>
                                    <li><a href="../projects/projects-invoice.html"><i class="dripicons-document"></i>Invoice</a></li>
                                    <li><a href="../projects/projects-chat.html"><i class="dripicons-conversation"></i>Chat</a></li>
                                    <li><a href="../projects/projects-users.html"><i class="dripicons-user-group"></i>Users</a></li>
                                </ul>
                            </li><!--end submenu-->
                            <li class="has-submenu">
                                <a href="#">
                                    <svg class="nav-svg" version="1.1" id="Layer_2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                        viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                        <g>
                                            <ellipse class="svg-primary" transform="matrix(0.9998 -1.842767e-02 1.842767e-02 0.9998 -7.7858 3.0205)" cx="160" cy="424" rx="24" ry="24"/>
                                            <ellipse class="svg-primary" transform="matrix(2.381651e-02 -0.9997 0.9997 2.381651e-02 -48.5107 798.282)" cx="384.5" cy="424" rx="24" ry="24"/>
                                            <path d="M463.8,132.2c-0.7-2.4-2.8-4-5.2-4.2L132.9,96.5c-2.8-0.3-6.2-2.1-7.5-4.7c-3.8-7.1-6.2-11.1-12.2-18.6
                                                c-7.7-9.4-22.2-9.1-48.8-9.3c-9-0.1-16.3,5.2-16.3,14.1c0,8.7,6.9,14.1,15.6,14.1c8.7,0,21.3,0.5,26,1.9c4.7,1.4,8.5,9.1,9.9,15.8
                                                c0,0.1,0,0.2,0.1,0.3c0.2,1.2,2,10.2,2,10.3l40,211.6c2.4,14.5,7.3,26.5,14.5,35.7c8.4,10.8,19.5,16.2,32.9,16.2h236.6
                                                c7.6,0,14.1-5.8,14.4-13.4c0.4-8-6-14.6-14-14.6H189h-0.1c-2,0-4.9,0-8.3-2.8c-3.5-3-8.3-9.9-11.5-26l-4.3-23.7
                                                c0-0.3,0.1-0.5,0.4-0.6l277.7-47c2.6-0.4,4.6-2.5,4.9-5.2l16-115.8C464,134,464,133.1,463.8,132.2z"/>
                                        </g>
                                    </svg>
                                    <span>Ecommerce</span>
                                </a>
                                <ul class="submenu">
                                    <li><a href="../ecommerce/ecommerce-index.html"><i class="dripicons-device-desktop"></i>Dashboard</a></li>
                                    <li><a href="../ecommerce/ecommerce-products.html"><i class="dripicons-view-apps"></i>Products</a></li>
                                    <li><a href="../ecommerce/ecommerce-product-list.html"><i class="dripicons-list"></i>Product List</a></li>
                                    <li><a href="../ecommerce/ecommerce-product-detail.html"><i class="dripicons-article"></i>Product Detail</a></li>
                                    <li><a href="../ecommerce/ecommerce-cart.html"><i class="dripicons-cart"></i>Cart</a></li>
                                    <li><a href="../ecommerce/ecommerce-checkout.html"><i class="dripicons-card"></i>Checkout</a></li>
                                </ul><!--end submenu-->
                            </li><!--end has-submenu-->
                            <li class="has-submenu">
                                <a href="#">
                                    <svg class="nav-svg" version="1.1" id="Layer_3" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                    viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                        <g>
                                            <g>
                                                <path d="M276,68.1v219c0,3.7-2.5,6.8-6,7.7L81.1,343.4c-2.3,0.6-3.6,3.1-2.7,5.4C109.1,426,184.9,480.6,273.2,480
                                                    C387.8,479.3,480,386.5,480,272c0-112.1-88.6-203.5-199.8-207.8C277.9,64.1,276,65.9,276,68.1z"/>
                                            </g>
                                            <path class="svg-primary" d="M32,239.3c0,0,0.2,48.8,15.2,81.1c0.8,1.8,2.8,2.7,4.6,2.2l193.8-49.7c3.5-0.9,6.4-4.6,6.4-8.2V36c0-2.2-1.8-4-4-4
                                                C91,33.9,32,149,32,239.3z"/>
                                        </g>
                                    </svg>
                                    <span>CRM</span>
                                </a>
                                <ul class="submenu">
                                    <li><a href="../crm/crm-index.html"><i class="dripicons-monitor"></i>Dashboard</a></li>
                                    <li><a href="../crm/crm-contacts.html"><i class="dripicons-user-id"></i>Contacts</a></li>
                                    <li><a href="../crm/crm-opportunities.html"><i class="dripicons-lightbulb"></i>Opportunities</a></li>
                                    <li><a href="../crm/crm-leads.html"><i class="dripicons-toggles"></i>Leads</a></li>
                                    <li><a href="../crm/crm-customers.html"><i class="dripicons-user-group"></i>Customers</a></li>
                                </ul><!--end submenu-->
                            </li><!--end has-submenu-->
                            
                            <li class="has-submenu">
                                <a href="#">
                                    <svg class="nav-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path d="M70.7 164.5l169.2 81.7c4.4 2.1 10.3 3.2 16.1 3.2s11.7-1.1 16.1-3.2l169.2-81.7c8.9-4.3 8.9-11.3 0-15.6L272.1 67.2c-4.4-2.1-10.3-3.2-16.1-3.2s-11.7 1.1-16.1 3.2L70.7 148.9c-8.9 4.3-8.9 11.3 0 15.6z"/>
                                        <path class="svg-primary" d="M441.3 248.2s-30.9-14.9-35-16.9-5.2-1.9-9.5.1S272 291.6 272 291.6c-4.5 2.1-10.3 3.2-16.1 3.2s-11.7-1.1-16.1-3.2c0 0-117.3-56.6-122.8-59.3-6-2.9-7.7-2.9-13.1-.3l-33.4 16.1c-8.9 4.3-8.9 11.3 0 15.6l169.2 81.7c4.4 2.1 10.3 3.2 16.1 3.2s11.7-1.1 16.1-3.2l169.2-81.7c9.1-4.2 9.1-11.2.2-15.5z"/>
                                        <path d="M441.3 347.5s-30.9-14.9-35-16.9-5.2-1.9-9.5.1S272.1 391 272.1 391c-4.5 2.1-10.3 3.2-16.1 3.2s-11.7-1.1-16.1-3.2c0 0-117.3-56.6-122.8-59.3-6-2.9-7.7-2.9-13.1-.3l-33.4 16.1c-8.9 4.3-8.9 11.3 0 15.6l169.2 81.7c4.4 2.2 10.3 3.2 16.1 3.2s11.7-1.1 16.1-3.2l169.2-81.7c9-4.3 9-11.3.1-15.6z"/>
                                    </svg>
                                    <span>UI Kit</span>
                                </a>
                                <ul class="submenu">
                                    
                                    <li class="has-submenu">
                                        <a href="#"><i class="dripicons-view-thumb"></i>UI Elements</a>
                                        <ul class="submenu">
                                            <li><a href="../others/ui-bootstrap.html">Bootstrap</a></li>
                                            <li><a href="../others/ui-animation.html">Animation</a></li>
                                            <li><a href="../others/ui-avatar.html">Avatar</a></li>
                                            <li><a href="../others/ui-clipboard.html">Clip Board</a></li>
                                            <li><a href="../others/ui-files.html">File Manager</a></li>
                                            <li><a href="../others/ui-ribbons.html">Ribbons</a></li>
                                            <li><a href="../others/ui-dragula.html">Dragula</a></li>
                                            <li><a href="../others/ui-check-radio.html">Check & Radio</a></li>
                                        </ul>
                                    </li><!--end has-submenu-->
                                    <li class="has-submenu">
                                        <a href="#"><i class="dripicons-anchor"></i>Advanced UI</a>
                                        <ul class="submenu">
                                            <li><a href="../others/advanced-rangeslider.html">Range Slider</a></li>
                                            <li><a href="../others/advanced-sweetalerts.html">Sweet Alerts</a></li>
                                            <li><a href="../others/advanced-nestable.html">Nestable List</a></li>
                                            <li><a href="../others/advanced-ratings.html">Ratings</a></li>
                                            <li><a href="../others/advanced-highlight.html">Highlight</a></li>
                                            <li><a href="../others/advanced-session.html">Session Timeout</a></li>
                                            <li><a href="../others/advanced-idle-timer.html">Idle Timer</a></li>
                                        </ul>
                                    </li><!--end has-submenu-->
                                    <li class="has-submenu">
                                        <a href="#"><i class="dripicons-document"></i>Forms</a>
                                        <ul class="submenu">
                                            <li><a href="../others/forms-elements.html">Basic Elements</a></li>
                                            <li><a href="../others/forms-advanced.html">Advance Elements</a></li>
                                            <li><a href="../others/forms-validation.html">Validation</a></li>
                                            <li><a href="../others/forms-wizard.html">Wizard</a></li>
                                            <li><a href="../others/forms-editors.html">Editors</a></li>
                                            <li><a href="../others/forms-repeater.html">Repeater</a></li>
                                            <li><a href="../others/forms-x-editable.html">X Editable</a></li>
                                            <li><a href="../others/forms-uploads.html">File Upload</a></li>
                                            <li><a href="../others/forms-img-crop.html">Image Crop</a></li>
                                        </ul>
                                    </li> <!--end has-submenu-->
                                    <li class="has-submenu">
                                        <a href="#"><i class="dripicons-graph-line"></i>Charts</a>
                                        <ul class="submenu">
                                            <li><a href="../others/charts-apex.html">Apex</a></li>
                                            <li><a href="../others/charts-morris.html">Morris</a></li>
                                            <li><a href="../others/charts-chartist.html">Chartist</a></li>
                                            <li><a href="../others/charts-flot.html">Flot</a></li>
                                            <li><a href="../others/charts-peity.html">Peity</a></li>
                                            <li><a href="../others/charts-chartjs.html">Chartjs</a></li>
                                            <li><a href="../others/charts-sparkline.html">Sparkline</a></li>
                                            <li><a href="../others/charts-knob.html">Jquery Knob</a></li>
                                            <li><a href="../others/charts-justgage.html">JustGage</a></li>                                            
                                        </ul>
                                    </li><!--end has-submenu-->                                    
                                    <li class="has-submenu">
                                        <a href="#"><i class="dripicons-view-list-large"></i>Tables </a>
                                        <ul class="submenu">
                                            <li><a href="../others/tables-basic.html">Basic</a></li>
                                            <li><a href="../others/tables-datatable.html">Datatables</a></li>
                                            <li><a href="../others/tables-responsive.html">Responsive</a></li>
                                            <li><a href="../others/tables-footable.html">Footable</a></li>
                                            <li><a href="../others/tables-jsgrid.html">Jsgrid</a></li>
                                            <li><a href="../others/tables-editable.html">Editable</a></li>
                                        </ul>
                                    </li><!--end has-submenu-->
                                    <li class="has-submenu">
                                        <a href="#"><i class="dripicons-headset"></i>Icons</a>
                                        <ul class="submenu">
                                            <li><a href="../others/icons-materialdesign.html">Material Design</a></li>
                                            <li><a href="../others/icons-dripicons.html">Dripicons</a></li>
                                            <li><a href="../others/icons-fontawesome.html">Font awesome</a></li>
                                            <li><a href="../others/icons-themify.html">Themify</a></li>
                                            <li><a href="../others/icons-typicons.html">Typicons</a></li>
                                            <li><a href="../others/icons-emoji.html">Emoji</a></li>
                                            <li><a href="../others/icons-svg.html">SVG</a></li>
                                        </ul>
                                    </li> <!--end has-submenu-->                                   
                                    <li class="has-submenu">
                                        <a href="#"><i class="dripicons-map"></i>Maps</a>
                                        <ul class="submenu">
                                            <li><a href="../others/maps-google.html">Google Maps</a></li>
                                            <li><a href="../others/maps-vector.html">Vector Maps</a></li>
                                        </ul>
                                    </li> <!--end has-submenu-->
                                    <li class="has-submenu">
                                        <a href="#"><i class="dripicons-mail"></i>Email</a>
                                        <ul class="submenu">
                                            <li><a href="../others/email-inbox.html">Inbox</a></li>
                                            <li><a href="../others/email-read.html">Read Email</a></li>
                                        </ul>
                                    </li> <!--end has-submenu--> 
                                    <li class="has-submenu">
                                        <a href="#"><i class="dripicons-article"></i>Email Templates</a>
                                        <ul class="submenu">
                                            <li><a href="../others/email-templates-basic.html">Basic Action Email</a></li>
                                            <li><a href="../others/email-templates-alert.html">Alert Email</a></li>
                                            <li><a href="../others/email-templates-billing.html">Billing Email</a></li>
                                        </ul>
                                    </li><!--end has-submenu-->                                   
                                </ul><!--end submenu-->
                            </li><!--end has-submenu-->

                            <li class="has-submenu">
                                <a href="#">
                                    <svg class="nav-svg" version="1.1" id="Layer_4" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                    viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                    <g>
                                        <path d="M462.5,352.3c-1.9-5.5-5.6-11.5-11.4-18.3c-10.2-12-30.8-29.3-54.8-47.2c-2.6-2-6.4-0.8-7.5,2.3l-4.7,13.4
                                            c-0.7,2,0,4.3,1.7,5.5c15.9,11.6,35.9,27.9,41.8,35.9c2,2.8-0.5,6.6-3.9,5.8c-10-2.3-29-7.3-44.2-12.8c-8.6-3.1-17.7-6.7-27.2-10.6
                                            c16-20.8,24.7-46.3,24.7-72.6c0-32.8-13.2-63.6-37.1-86.4c-22.9-21.9-53.8-34.1-85.7-33.7c-25.7,0.3-50.1,8.4-70.7,23.5
                                            c-18.3,13.4-32.2,31.3-40.6,52c-8.3-6-16.1-11.9-23.2-17.6c-13.7-10.9-28.4-22-38.7-34.7c-2.2-2.8,0.9-6.7,4.4-5.9
                                            c11.3,2.6,35.4,10.9,56.4,18.9c1.5,0.6,3.2,0.3,4.5-0.8l11.1-10.1c2.4-2.1,1.7-6-1.3-7.2C121,137.4,89.2,128,73.2,128
                                            c-11.5,0-19.3,3.5-23.3,10.4c-7.6,13.3,7.1,35.2,45.1,66.8c34.1,28.5,82.6,61.8,136.5,92c87.5,49.1,171.1,81,208,81
                                            c11.2,0,18.7-3.1,22.1-9.1C464.4,364.4,464.7,358.7,462.5,352.3z"/>
                                        <path  class="svg-primary" d="M312,354c-29.1-12.8-59.3-26-92.6-44.8c-30.1-16.9-59.4-36.5-84.4-53.6c-1-0.7-2.2-1.1-3.4-1.1c-0.9,0-1.9,0.2-2.8,0.7
                                            c-2,1-3.3,3-3.3,5.2c0,1.2-0.1,2.4-0.1,3.5c0,32.1,12.6,62.3,35.5,84.9c22.9,22.7,53.4,35.2,85.8,35.2c23.6,0,46.5-6.7,66.2-19.5
                                            c1.9-1.2,2.9-3.3,2.7-5.5C315.5,356.8,314.1,354.9,312,354z"/>
                                    </g>
                                    </svg>        
                                    <span>Pages</span>
                                </a>
                                <ul class="submenu">
                                    <li><a href="../pages/pages-profile.html"><i class="dripicons-user"></i>Profile</a></li>
                                    <li><a href="../pages/pages-chat.html"><i class="dripicons-conversation"></i>Chat</a></li>
                                    <li><a href="../pages/pages-contact-list.html"><i class="dripicons-user-id"></i>Contact List</a></li>
                                    <li><a href="../pages/pages-tour.html"><i class="dripicons-rocket"></i>Tour</a></li>
                                    <li><a href="../pages/pages-timeline.html"><i class="dripicons-clock"></i>Timeline</a></li>
                                    <li><a href="../pages/pages-invoice.html"><i class="dripicons-document"></i>Invoice</a></li>
                                    <li><a href="../pages/pages-treeview.html"><i class="dripicons-network-3"></i>Treeview</a></li>
                                    <li><a href="../pages/pages-starter.html"><i class="dripicons-clipboard"></i>Starter</a></li>
                                    <li><a href="../pages/pages-pricing.html"><i class="dripicons-article"></i>Pricing</a></li>
                                    <li><a href="../pages/pages-blogs.html"><i class="dripicons-blog"></i>Blogs</a></li>
                                    <li><a href="../pages/pages-faq.html"><i class="dripicons-question"></i>FAQ</a></li>
                                    <li><a href="../pages/pages-gallery.html"><i class="dripicons-photo-group"></i>Gallery</a></li>
                                </ul>
                            </li><!--end has-submenu-->

                            <li class="has-submenu">
                                <a href="#">
                                    <svg class="nav-svg" version="1.1" id="Layer_5" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                    viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                        <g>
                                            <path class="svg-primary" d="M376,192h-24v-46.7c0-52.7-42-96.5-94.7-97.3c-53.4-0.7-97.3,42.8-97.3,96v48h-24c-22,0-40,18-40,40v192c0,22,18,40,40,40
                                                h240c22,0,40-18,40-40V232C416,210,398,192,376,192z M270,316.8v68.8c0,7.5-5.8,14-13.3,14.4c-8,0.4-14.7-6-14.7-14v-69.2
                                                c-11.5-5.6-19.1-17.8-17.9-31.7c1.4-15.5,14.1-27.9,29.6-29c18.7-1.3,34.3,13.5,34.3,31.9C288,300.7,280.7,311.6,270,316.8z
                                                    M324,192H188v-48c0-18.1,7.1-35.1,20-48s29.9-20,48-20s35.1,7.1,48,20s20,29.9,20,48V192z"/>
                                        </g>
                                    </svg>
                                    <span>Authentication</span>
                                </a>
                                <ul class="submenu">
                                    <li><a href="../authentication/auth-login.html"><i class="dripicons-enter"></i>Log In</a></li>
                                    <li><a href="../authentication/auth-register.html"><i class="dripicons-pencil"></i>Register</a></li>
                                    <li><a href="../authentication/auth-recover-pw.html"><i class="dripicons-clockwise"></i>Recover Password</a></li>
                                    <li><a href="../authentication/auth-lock-screen.html"><i class="dripicons-lock"></i>Lock Screen</a></li>
                                    <li><a href="../authentication/auth-404.html"><i class="dripicons-warning"></i>Error 404</a></li>
                                    <li><a href="../authentication/auth-500.html"><i class="dripicons-wrong"></i>Error 500</a></li>
                                </ul>
                            </li><!--end has-submenu-->
                        </ul><!-- End navigation menu -->
                    </div> <!-- end navigation -->
                </div> <!-- end container-fluid -->
            </div> <!-- end navbar-custom -->
        </div>
        <!-- Top Bar End -->

        <div class="page-wrapper">
            

            <!-- Page Content-->
            <div class="page-content">

                <div class="container-fluid">
                    <!-- Page-Title -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="page-title-box">
                                <div class="float-right">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Metrica</a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Crypto</a></li>
                                        <li class="breadcrumb-item active">Dashboard</li>
                                    </ol><!--end breadcrumb-->
                                </div><!--end /div-->
                                <h4 class="page-title">Dashboard</h4>
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div><!--end row-->
                    <!-- end page title end breadcrumb -->
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="crypto-report-history d-flex justify-content-end">
                                        <ul class="nav">
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Hour</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#">Day</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Week</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Month</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Order Book</a>
                                            </li>                                        
                                        </ul>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="media">
                                                <img src="../assets/images/coins/btc.png" class="mr-3 thumb-sm align-self-center rounded-circle" alt="...">
                                                <div class="media-body align-self-center"> 
                                                    <div class="coin-bal">                                                        
                                                        <h4 class="m-0">$7289.45</h4>
                                                        <p class="text-muted mb-0">Bitcoin 
                                                            <span class="text-muted font-12">(BTC)</span>
                                                            <span class="text-success">2.5% <i class="mdi mdi-arrow-up"></i></span>
                                                        </p>
                                                    </div>                                                                                              
                                                </div><!--end media body-->
                                            </div><!--end col--> 
                                        </div><!--end col--> 
                                        <div class="col-md-3">
                                            <p class="mb-0 p-2 bg-soft-dark rounded"><b>Last: </b>0.25436584</p>
                                        </div><!--end col--> 
                                        <div class="col-md-3">
                                            <p class="mb-0 p-2 bg-soft-success rounded"><b>24High: </b>0.25436584</p>
                                        </div><!--end col--> 
                                        <div class="col-md-3">
                                            <p class="mb-0 p-2 bg-soft-danger rounded"><b>24Low: </b>0.25436584</p>
                                        </div><!--end col--> 
                                    </div><!-- end row -->                                            
                                    <div id="crypto_dash_main" class="apex-charts"></div>
                                </div><!--end card-body--> 
                            </div><!--end card--> 
                        </div><!--end col--> 

                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="wallet-bal-usd">
                                                <h4 class="wallet-title m-0">Your Total Balence</h4>
                                                <span class="text-muted font-12">30 june 2019</span>
                                                <h3 class="text-center">$85692.00</h3>
                                            </div> 
                                            <p class="font-15 text-success text-center mb-4"> + $455.00 <span class="font-12 text-muted">(6.2% <i class="mdi mdi-trending-up text-success"></i>)</span></p>
                                            <div class="text-right">
                                                <button class="btn btn-success btn-sm px-3">Receive</button>
                                                <button class="btn btn-danger btn-sm px-3">Send</button>
                                                <button class="btn btn-primary btn-sm px-3">+ Invest</button>
                                            </div>
                                        </div>                                        
                                    </div>                                               
                                </div><!--end card-body-->
                                <div class="card-body pt-0">
                                    <ul class="list-group wallet-bal-crypto">
                                        <li class="list-group-item align-items-center d-flex justify-content-between">
                                            <div class="media">
                                                <img src="../assets/images/coins/btc.png" class="mr-3 thumb-sm align-self-center rounded-circle" alt="...">
                                                <div class="media-body align-self-center"> 
                                                    <div class="coin-bal">                                                        
                                                        <h3 class="m-0">6.18424000</h3>
                                                        <p class="text-muted mb-0">$ 33277.3660</p>
                                                    </div>                                                                                              
                                                </div><!--end media body-->
                                            </div>
                                            <span class="badge badge-soft-purple">Bitcoin</span>
                                        </li>
                                        <li class="list-group-item align-items-center d-flex justify-content-between">
                                            <div class="media">
                                                <img src="../assets/images/coins/mon.png" class="mr-3 thumb-sm align-self-center rounded-circle" alt="...">
                                                <div class="media-body align-self-center"> 
                                                    <div class="coin-bal">                                                        
                                                        <h3 class="m-0">60.1842</h3>
                                                        <p class="text-muted mb-0">$ 18564.3660</p>
                                                    </div>                                                                                              
                                                </div><!--end media body-->
                                            </div>
                                            <span class="badge badge-soft-info">Monero</span>
                                        </li>
                                        <li class="list-group-item align-items-center d-flex justify-content-between">
                                            <div class="media">
                                                <img src="../assets/images/coins/eth.png" class="mr-3 thumb-sm align-self-center rounded-circle" alt="...">
                                                <div class="media-body align-self-center"> 
                                                    <div class="coin-bal">                                                        
                                                        <h3 class="m-0">32.65849212</h3>
                                                        <p class="text-muted mb-0">$ 33277.3660</p>
                                                    </div>                                                                                              
                                                </div><!--end media body-->
                                            </div>
                                            <span class="badge badge-soft-success">Ethereum</span>
                                        </li>
                                    </ul> 
                                </div><!--end card-body--> 
                            </div><!--end card--> 
                        </div><!--end col-->
                    </div> <!-- end row -->

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body ">
                                    <a href="" class="float-right text-info">View All</a>
                                    <h4 class="header-title mt-0 mb-3">Transaction History</h4>
                                    <ul class="list-unsyled m-0 pl-0 transaction-history">
                                        <li class="align-items-center d-flex justify-content-between">
                                            <div class="media">
                                                <div class="transaction-icon">
                                                    <i class="mdi mdi-arrow-top-right-thick"></i>
                                                </div>                                                
                                                <div class="media-body align-self-center"> 
                                                    <div class="transaction-data">                                                        
                                                        <h3 class="m-0">Send BTC</h3>
                                                        <p class="text-muted mb-0">6 June 2019 10:25 AM</p>
                                                    </div>                                                                                              
                                                </div><!--end media body-->
                                            </div>
                                            <span class="text-danger">0.000245684 BTC</span>
                                        </li>
                                        <li class="align-items-center d-flex justify-content-between">
                                            <div class="media">
                                                <div class="transaction-icon">
                                                    <i class="mdi mdi-arrow-down-thick"></i>
                                                </div>                                                
                                                <div class="media-body align-self-center"> 
                                                    <div class="transaction-data">                                                        
                                                        <h3 class="m-0">Receive BTC</h3>
                                                        <p class="text-muted mb-0">4 June 2019 7:05 PM</p>
                                                    </div>                                                                                              
                                                </div><!--end media body-->
                                            </div>
                                            <span class="text-success">0.012458632 BTC</span>
                                        </li>
                                        <li class="align-items-center d-flex justify-content-between">
                                            <div class="media">
                                                <div class="transaction-icon">
                                                    <i class="mdi mdi-arrow-top-right-thick"></i>
                                                </div>                                                
                                                <div class="media-body align-self-center"> 
                                                    <div class="transaction-data">                                                        
                                                        <h3 class="m-0">Send BTC</h3>
                                                        <p class="text-muted mb-0">1 June 2019 11:30 PM</p>
                                                    </div>                                                                                              
                                                </div><!--end media body-->
                                            </div>
                                            <span class="text-danger">0.000245684 BTC</span>
                                        </li>
                                        <li class="align-items-center d-flex justify-content-between">
                                            <div class="media">
                                                <div class="transaction-icon">
                                                    <i class="mdi mdi-arrow-top-right-thick"></i>
                                                </div>                                                
                                                <div class="media-body align-self-center"> 
                                                    <div class="transaction-data">                                                        
                                                        <h3 class="m-0">Send BTC</h3>
                                                        <p class="text-muted mb-0">28 May 2019 08:45 AM</p>
                                                    </div>                                                                                              
                                                </div><!--end media body-->
                                            </div>
                                            <span class="text-danger">0.000245684 BTC</span>
                                        </li>
                                        <li class="align-items-center d-flex justify-content-between">
                                            <div class="media">
                                                <div class="transaction-icon">
                                                    <i class="mdi mdi-arrow-down-thick"></i>
                                                </div>                                                
                                                <div class="media-body align-self-center"> 
                                                    <div class="transaction-data">                                                        
                                                        <h3 class="m-0">Receive BTC</h3>
                                                        <p class="text-muted mb-0">25 May 2019 01:25 PM</p>
                                                    </div>                                                                                              
                                                </div><!--end media body-->
                                            </div>
                                            <span class="text-success">0.012458632 BTC</span>
                                        </li>
                                    </ul>                                    
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->
                        
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title mt-0 mb-3">Market cap</h4>
                                    <div class="table-responsive market-cap-table">
                                        <table class="table mb-0">
                                            <thead class="thead-light">
                                            <tr>
                                                <th>Name</th>
                                                <th class="text-right">Market cap</th>
                                                <th class="text-right">Volume(24h)</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <div class="media">
                                                        <img src="../assets/images/coins/btc.png" class="mr-3 thumb-sm align-self-center rounded-circle" alt="...">
                                                        <div class="media-body align-self-center"> 
                                                            <div class="coin-bal">                                                        
                                                                <h5 class="m-0">$7289.45</h5>
                                                                <p class="text-muted mb-0">Bitcoin 
                                                                    <span class="text-muted font-12">(BTC)</span>
                                                                    <span class="text-success">2.5% <i class="mdi mdi-arrow-up"></i></span>
                                                                </p>
                                                            </div>                                                                                              
                                                        </div><!--end media body-->
                                                    </div> <!--end media-->               
                                                </td>
                                                <td class="text-right">$129,820,932</td>
                                                <td class="text-right">$25,111,773</td>
                                            </tr><!--end tr-->
                                            <tr>
                                                <td>
                                                    <div class="media">
                                                        <img src="../assets/images/coins/eth.png" class="mr-3 thumb-sm align-self-center rounded-circle" alt="...">
                                                        <div class="media-body align-self-center"> 
                                                            <div class="coin-bal">                                                        
                                                                <h5 class="m-0">$234.45</h5>
                                                                <p class="text-muted mb-0">Ethereum 
                                                                    <span class="text-muted font-12">(ETH)</span>
                                                                    <span class="text-success">0.45% <i class="mdi mdi-arrow-up"></i></span>
                                                                </p>
                                                            </div>                                                                                              
                                                        </div><!--end media body-->
                                                    </div> <!--end media-->               
                                                </td>
                                                <td class="text-right">$24,909,743</td>
                                                <td class="text-right">$12,856,403</td>
                                            </tr><!--end tr-->
                                            <tr>
                                                <td>
                                                    <div class="media">
                                                        <img src="../assets/images/coins/lite.png" class="mr-3 thumb-sm align-self-center rounded-circle" alt="...">
                                                        <div class="media-body align-self-center"> 
                                                            <div class="coin-bal">                                                        
                                                                <h5 class="m-0">$7289.45</h5>
                                                                <p class="text-muted mb-0">Litecoin 
                                                                    <span class="text-muted font-12">(LTC)</span>
                                                                    <span class="text-success">3.51% <i class="mdi mdi-arrow-up"></i></span>
                                                                </p>
                                                            </div>                                                                                              
                                                        </div><!--end media body-->
                                                    </div> <!--end media-->               
                                                </td>
                                                <td class="text-right">$5,399,795,666</td>
                                                <td class="text-right">$4,394,304,674</td>
                                            </tr><!--end tr-->
                                            <tr>
                                                <td>
                                                    <div class="media">
                                                        <img src="../assets/images/coins/mon.png" class="mr-3 thumb-sm align-self-center rounded-circle" alt="...">
                                                        <div class="media-body align-self-center"> 
                                                            <div class="coin-bal">                                                        
                                                                <h5 class="m-0">$7289.45</h5>
                                                                <p class="text-muted mb-0">Monero 
                                                                    <span class="text-muted font-12">(XMR)</span>
                                                                    <span class="text-success">0.95% <i class="mdi mdi-arrow-up"></i></span>
                                                                </p>
                                                            </div>                                                                                              
                                                        </div><!--end media body-->
                                                    </div> <!--end media-->               
                                                </td>
                                                <td class="text-right">$1,375,808,437</td>
                                                <td class="text-right">$58,791,421	</td>
                                            </tr><!--end tr-->
                                            <tr>
                                                <td>
                                                    <div class="media">
                                                        <img src="../assets/images/coins/dash.png" class="mr-3 thumb-sm align-self-center rounded-circle" alt="...">
                                                        <div class="media-body align-self-center"> 
                                                            <div class="coin-bal">                                                        
                                                                <h5 class="m-0">$7289.45</h5>
                                                                <p class="text-muted mb-0">Dashcoin 
                                                                    <span class="text-muted font-12">(DASH)</span>
                                                                    <span class="text-success">4.3% <i class="mdi mdi-arrow-up"></i></span>
                                                                </p>
                                                            </div>                                                                                              
                                                        </div><!--end media body-->
                                                    </div> <!--end media-->               
                                                </td>
                                                <td class="text-right">$1,205,400,168</td>
                                                <td class="text-right">$368,553,228</td>
                                            </tr><!--end tr-->
                                            </tbody>
                                        </table><!--end /table-->
                                    </div><!--end /tableresponsive-->                                    
                                </div><!--end card-body--> 
                            </div><!--end card--> 
                        </div><!--end col-->                                       
                    </div><!--end row--> 
                    
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="card">
                                <div class="card-body">
                                        <h3 class="vol-btc float-md-right float-sm-none">BTC 7324.88<small class="text-muted">(USD)</small></h3>
                                    <h4 class="header-title mt-0 mb-3">BTC Volume by Currency</h4>
                                    
                                    <div id="animating-donut" class="ct-chart ct-golden-section my-5 btc-volume-chart"></div>

                                    
                                    
                                    <ul class="list-unstyled list-inline text-center d-flex justify-content-around mb-0">
                                        <li class="list-inline-item mt-2">
                                            <h5 class="mb-1 mt-1 font-16">1.248.88</h5>
                                            <span class="font-14 text-info"><i class="mdi mdi-checkbox-blank-circle mr-2 text-purple"></i>JPY </span> 
                                            <span class="text-danger">20.1% <i class="mdi mdi-trending-down"></i></span>
                                        </li>
                                        <li class="list-inline-item mt-2">
                                            <h5 class="mb-1 mt-1 font-16">14,351.25</h5>
                                            <span class="text-info font-14"><i class="mdi mdi-checkbox-blank-circle mr-2 text-secondary"></i>USD</span>                                            
                                            <span class="text-success">22.9% <i class="mdi mdi-trending-up"></i></span>
                                        </li>
                                        <li class="list-inline-item mt-2">                                            
                                            <h5 class="mb-1 mt-1 font-16">5,547.21</h5>
                                            <span class="text-info font-14"><i class="mdi mdi-checkbox-blank-circle mr-2 text-success"></i>KRW</span>
                                            <span class="text-success">14.2% <i class="mdi mdi-trending-up"></i></span>
                                        </li>    
                                        <li class="list-inline-item mt-2">                                            
                                            <h5 class="mb-1 mt-1 font-16">3,477.23</h5>
                                            <span class="text-info font-14"><i class="mdi mdi-checkbox-blank-circle mr-2 text-warning"></i>EUR</span>
                                            <span class="text-success">08.1% <i class="mdi mdi-trending-up"></i></span>
                                        </li>                                     
                                    </ul>             
                                </div><!--end card-body-->
                            </div><!--end card-->                           
                        </div> <!--end col--> 
                        <div class="col-lg-7">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mt-0 mb-3 header-title">Historical Bitcoin Price and Volume</h4>
                                    <script type="text/javascript" src="https://widgets.cryptocompare.com/serve/v1/coin/histo_week?fsym=BTC&amp;tsym=USD&amp;app=www.cryptocompare.com"></script>
                                </div><!--end card-body--> 
                            </div><!--end card--> 
                        </div><!--end col--> 
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="media">
                                        <img src="../assets/images/small/news-3.jpg" height="90" class="mr-3" alt="...">
                                        <div class="media-body align-self-center">  
                                            <div class="mb-2">
                                                <span class="badge badge-purple px-3">Crypto</span>
                                                <span class="ml-2 text-muted">26 mars 2019</span>
                                            </div>
                                            <a href="" class="font-16 d-block font-weight-normal">It is a long established fact that a reader will be 
                                                distracted of a page. 
                                            </a>                                             
                                        </div><!--end media body-->
                                    </div><!--end media-->        
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->       

                        <div class="col-lg-4">                            
                            <div class="card">
                                <div class="card-body">
                                    <div class="media">
                                        <img src="../assets/images/small/news-1.jpg" height="90" class="mr-3" alt="...">
                                        <div class="media-body align-self-center">  
                                            <div class="mb-2">
                                                <span class="badge badge-secondary px-3">Crypto</span>
                                                <span class="ml-2 text-muted">26 mars 2019</span>
                                            </div>
                                            <a href="" class="font-16 d-block font-weight-normal">It is a long established fact that a reader will be 
                                                distracted of a page. 
                                            </a>                                             
                                        </div><!--end media body-->
                                    </div><!--end media-->        
                                </div><!--end card-body-->
                            </div><!--end card-->                              
                        </div> <!--end col-->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="media">
                                        <img src="../assets/images/small/news-2.jpg" height="90" class="mr-3" alt="...">
                                        <div class="media-body align-self-center">  
                                            <div class="mb-2">
                                                <span class="badge badge-purple px-3">Crypto</span>
                                                <span class="ml-2 text-muted">26 mars 2019</span>
                                            </div>
                                            <a href="" class="font-16 d-block font-weight-normal">It is a long established fact that a reader will be 
                                                distracted of a page. 
                                            </a>                                             
                                        </div><!--end media body-->
                                    </div><!--end media-->        
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col--> 
                    </div><!--end row--> 
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="card">
                                <div class="card-body"> 
                                    <a href="" class="float-right text-info">View All</a>
                                    <h4 class="header-title mt-0 mb-3">Timeline</h4>
                                    <div class="slimscroll crypto-dash-activity">
                                        <div class="activity">
                                            <i class="mdi mdi-checkbox-marked-circle-outline icon-success"></i>
                                            <div class="time-item">
                                                <div class="item-info">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <h6 class="m-0">Highest Account Value</h6>
                                                        <span class="text-muted">05 june 2019</span>
                                                    </div>
                                                    <p class="text-muted mt-3">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration.
                                                        <a href="#" class="text-info">[more info]</a>
                                                    </p>
                                                    <div>
                                                        <span class="badge badge-soft-secondary">Design</span>
                                                        <span class="badge badge-soft-secondary">HTML</span>                                                    
                                                        <span class="badge badge-soft-secondary">Css</span>                                                    
                                                    </div>
                                                </div>
                                            </div>
                                            <i class="mdi mdi-timer-off icon-pink"></i>                                                                                                           
                                            <div class="time-item">
                                                <div class="item-info">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <h6 class="m-0">Monthly Report</h6>
                                                        <span class="text-muted">28 May 2019</span>
                                                    </div>
                                                    <p class="text-muted mt-3">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration.
                                                        <a href="#" class="text-info">[more info]</a>
                                                    </p>
                                                    <div>
                                                        <span class="badge badge-soft-secondary">Python</span>
                                                        <span class="badge badge-soft-secondary">Django</span>
                                                    </div>
                                                </div>                                            
                                            </div>
                                            <i class="mdi mdi-alert-decagram icon-purple"></i> 
                                            <div class="time-item">
                                                <div class="item-info">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <h6 class="m-0">First Trade</h6>
                                                        <span class="text-muted">15 May 2019</span>
                                                    </div>
                                                    <p class="text-muted mt-3">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration.
                                                        <a href="#" class="text-info">[more info]</a>
                                                    </p>
                                                </div>
                                            </div>
                                            <i class="mdi mdi-clipboard-alert icon-warning"></i>
                                            <div class="time-item">
                                                <div class="item-info">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <h6 class="m-0">Sign Up</h6>
                                                        <span class="text-muted">02 Jan 2019</span>
                                                    </div>
                                                    <p class="text-muted mt-3">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration.
                                                        <a href="#" class="text-info">[more info]</a>
                                                    </p>                                                
                                                </div>
                                                <i class="mdi mdi-checkbox-marked-circle-outline icon-success"></i>
                                                <div class="time-item">
                                                    <div class="item-info">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h6 class="m-0">Highest Account Value</h6>
                                                            <span class="text-muted">05 june 2019</span>
                                                        </div>
                                                        <p class="text-muted mt-3">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration.
                                                            <a href="#" class="text-info">[more info]</a>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>                                                                                                                        
                                        </div><!--end activity-->
                                    </div><!--end crypto-dash-activity-->
                                </div><!--end card-body-->
                            </div><!--end card--> 
                        </div><!--end col--> 
                        <div class="col-lg-5">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mt-0 header-title mb-3">Currency Cacculater</h4>                                            
                                    <div class="calculator-block">                        
                                        <div class="calculator-body">                                                      
                                            <script src="https://www.cryptonator.com/ui/js/widget/calc_widget.js"></script>
                                        </div>
                                    </div>
                                </div><!--end card-body--> 
                            </div><!--end card-->
                            <div class="card">                                       
                                <div class="card-body">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-pills" role="tablist">
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link active" data-toggle="tab" href="#buy_coins" role="tab" aria-selected="true">Buy</a>
                                        </li>
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#sell_coins" role="tab" aria-selected="false">Sell</a>
                                        </li>
                                    </ul>
    
                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="buy_coins" role="tabpanel">
                                            <form class="form-horizontal">
                                                <div class="form-group mb-0">
                                                    <div class="input-group mt-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text bg-light" id="basic-addon1">Amount</span>
                                                        </div>
                                                        <input type="text" class="form-control" placeholder="123" aria-label="123" aria-describedby="basic-addon1">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text bg-light" id="basic-addon2">BTC</span>
                                                        </div>
                                                    </div>
                                                    <div class="input-group mt-3">
                                                        <div class="input-group-prepend">
                                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Price <i class="mdi mdi-chevron-down ml-1"></i></button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="#">Last Trade Price</a>
                                                                <a class="dropdown-item" href="#">Last Buy Price</a>
                                                                <a class="dropdown-item" href="#">Last Sell Price</a>
                                                            </div>
                                                        </div>
                                                        <input type="email" id="example-input2-group3" name="example-input2-group3" class="form-control" placeholder="$ 24,00">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text bg-light" id="basic-addon4">$ Dollor</span>
                                                        </div>                                                    
                                                    </div>
                                                    <div class="input-group mt-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text bg-light" id="basic-addon5">Total</span>
                                                        </div>
                                                        <input type="text" class="form-control" placeholder="123" aria-label="123" aria-describedby="basic-addon1">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text bg-light" id="basic-addon6">$ Dollor</span>
                                                        </div>
                                                    </div>        
                                                    <div class=" mt-3 ml-auto">
                                                        <a href="#" class="btn btn-success btn-sm">Buy Coins</a>
                                                    </div>
                                                </div> <!--end form-group-->           
                                            </form> <!--end form--> 
                                        </div>
                                        <div class="tab-pane" id="sell_coins" role="tabpanel">
                                            <form class="form-horizontal">
                                                <div class="form-group mb-0">
                                                    <div class="input-group mt-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text bg-light" id="basic-addon7">Amount</span>
                                                        </div>
                                                        <input type="text" class="form-control" placeholder="123" aria-label="123" aria-describedby="basic-addon1">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text bg-light" id="basic-addon8">BTC</span>
                                                        </div>
                                                    </div>
                                                    <div class="input-group mt-3">
                                                        <div class="input-group-prepend">
                                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Price <i class="mdi mdi-chevron-down ml-1"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="#">Last Trade Price</a>
                                                                <a class="dropdown-item" href="#">Last Buy Price</a>
                                                                <a class="dropdown-item" href="#">Last Sell Price</a>
                                                            </div>
                                                        </div>
                                                        <input type="email" id="example-input2-group4" name="example-input2-group4" class="form-control" placeholder="$ 24,00">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text bg-light" id="basic-addon9">$ Dollor</span>
                                                        </div>
                                                            
                                                    </div>
                                                    <div class="input-group mt-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text bg-light" id="basic-addon10">Total</span>
                                                        </div>
                                                        <input type="text" class="form-control" placeholder="123" aria-label="123" aria-describedby="basic-addon1">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text bg-light" id="basic-addon11">$ Dollor</span>
                                                        </div>
                                                    </div>    
                                                    <div class="mt-3 ml-auto">
                                                        <a href="#" class="btn btn-danger btn-sm">Sell Coins</a>
                                                    </div>
                                                </div> <!--end form-group-->           
                                            </form> <!--end form-->
                                        </div>
                                    </div> 
                                </div>  <!--end card-body-->                                     
                            </div><!--end card-->  
                        </div><!--end col-->  
                            
                    </div><!--end row-->  

                </div><!-- container -->
            </div>
            <!-- end page content -->
            <footer class="footer text-center text-sm-left">
               <div class="boxed-footer">
                    &copy; 2019 Metrica <span class="text-muted d-none d-sm-inline-block float-right">Crafted with <i class="mdi mdi-heart text-danger"></i> by Mannatthemes</span>
               </div>
            </footer><!--end footer-->
        </div>
        <!-- end page-wrapper -->

        <!-- jQuery  -->
        <script src="../assets/js/jquery.min.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/metisMenu.min.js"></script>
        <script src="../assets/js/waves.min.js"></script>
        <script src="../assets/js/jquery.slimscroll.min.js"></script>

        <script src="../assets/plugins/apexcharts/apexcharts.min.js"></script>
        <script src="../assets/plugins/apexcharts/irregular-data-series.js"></script>

        <!--Chartist Chart-->
        <script src="../assets/plugins/chartist/js/chartist.min.js"></script>
        <script src="../assets/plugins/chartist/js/chartist-plugin-tooltip.min.js"></script>
        <script src="../assets/pages/jquery.crypto-dashboard.init.js"></script>

        <!-- App js -->
        <script src="../assets/js/app.js"></script>
       
    </body>
</html>