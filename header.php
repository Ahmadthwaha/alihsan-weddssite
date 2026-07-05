<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Al Ihsan Da'awa College</title>
    <meta name="description" content="Official Website of Al Ihsan Da'awa College - Empowering students with Islamic values and modern education.">
    <meta name="keywords" content="Al Ihsan Da'awa College, Al Ihsan, Da'awa College, ADSA, Udupi College">

    <!-- Open Graph / Facebook Meta Tags -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="http://localhost/alihsan/">
    <meta property="og:title" content="Al Ihsan Da'awa College">
    <meta property="og:description" content="Official Website of Al Ihsan Da'awa College">
    <meta property="og:image" content="assets/images/logo/logo_round.png?v=2">
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://alihsan.edu.in/">
    <meta property="twitter:title" content="Al Ihsan Da'awa College">
    <meta property="twitter:description" content="Empowering the community with moral and general education.">
    <meta property="twitter:image" content="assets/images/logo/logo_round.png?v=2">
    
    <link rel="icon" type="image/x-icon" href="assets/images/logo/logo_round.png?v=2">
    <!-- animate css -->
    <link rel="stylesheet" href="assets/css/plugins/animate.min.css">
    <!-- fontawesome 6.4.2 -->
    <link rel="stylesheet" href="assets/css/plugins/fontawesome.min.css">
    <!-- bootstrap min css -->
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <!-- swiper Css 10.2.0 -->
    <link rel="stylesheet" href="assets/css/plugins/swiper.min.css">
    <!-- Bootstrap 5.0.2 -->
    <link rel="stylesheet" href="assets/css/vendor/magnific-popup.css">
    <!-- metismenu scss -->
    <link rel="stylesheet" href="assets/css/vendor/metismenu.css">
    <!-- nice select js -->
    <link rel="stylesheet" href="assets/css/plugins/nice-select.css">
    <link rel="stylesheet" href="assets/css/plugins/jquery-ui.css">
    <!-- custom style css -->
    <link rel="stylesheet" href="assets/css/style3.css">
    <style>
        /* Mobile Sidebar Styles */
        .side-bar-container {
            position: fixed;
            top: 0;
            right: -320px; /* Hidden offscreen by default */
            width: 320px;
            height: 100vh;
            background: #1b365d; /* Dark navy */
            box-shadow: -5px 0 25px rgba(0,0,0,0.15);
            z-index: 9999;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
            padding: 25px 20px;
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
        }

        .side-bar-container.show {
            right: 0; /* Slide in */
        }

        .side-bar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 25px;
        }

        .side-bar-header .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #ffffff;
            padding: 6px 12px;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .close-icon-menu {
            background: none;
            border: none;
            color: #ffffff;
            font-size: 24px;
            cursor: pointer;
            transition: color 0.2s;
            padding: 5px;
            line-height: 1;
        }

        .close-icon-menu:hover {
            color: #c5a85c;
        }

        .mobile-nav-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
            overflow-y: auto;
        }

        .mobile-nav-list li {
            width: 100%;
        }

        .mobile-nav-link {
            color: #ffffff;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            display: block;
            padding: 10px 0;
            transition: color 0.2s;
        }

        .mobile-nav-link:hover {
            color: #c5a85c;
        }

        .submenu-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .mobile-nav-link-toggle {
            background: none;
            border: none;
            color: #ffffff;
            font-size: 14px;
            cursor: pointer;
            padding: 10px;
            transition: color 0.2s, transform 0.2s;
        }

        .mobile-nav-link-toggle:hover {
            color: #c5a85c;
        }

        .mobile-submenu {
            list-style: none;
            padding-left: 20px;
            margin-top: 5px;
            display: none; /* Collapsed by default */
            border-left: 1px dashed rgba(255,255,255,0.2);
            flex-direction: column;
            gap: 5px;
        }

        .mobile-submenu li a {
            color: rgba(255,255,255,0.8);
            font-size: 14px;
            text-decoration: none;
            display: block;
            padding: 8px 0;
            transition: color 0.2s;
        }

        .mobile-submenu li a:hover {
            color: #c5a85c;
        }

        /* Backdrop filter and blur */
        #anywhere-home.bgshow {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(13, 30, 61, 0.6);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            z-index: 9998;
            transition: all 0.3s ease;
        }

        /* Hamburger Menu Icon */
        .hamburger-toggle-btn {
            display: none; /* Hidden on desktop */
            background: #ffffff;
            border: 1px solid rgba(0,0,0,0.05);
            color: #1b365d;
            font-size: 22px;
            cursor: pointer;
            padding: 8px 14px;
            border-radius: 6px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.2s;
        }

        .hamburger-toggle-btn:hover {
            background: #1b365d;
            color: #ffffff;
            border-color: #1b365d;
        }

        @media (max-width: 991px) {
            .header__menu {
                display: none !important;
            }
            .hamburger-toggle-btn {
                display: block !important;
            }
            .header__wrapper {
                display: flex;
                justify-content: space-between;
                align-items: center;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <!-- header area start -->
    <header class="header header__sticky v__1">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="header__wrapper">
                        <div class="header__logo" style="background: #ffffff; padding: 6px 15px; border-radius: 6px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-top: 5px; margin-bottom: 5px;">
                            <a href="index.php" class="header__logo--link" style="display: flex; align-items: center; gap: 12px; text-decoration: none;">
                                <img src="assets/images/logo/logo_round.png?v=2" style="height: 52px; width: auto; object-fit: contain;" alt="Al Ihsan Logo">
                                <span class="logo-text" style="font-size: 15px; font-weight: 800; color: #1b365d; line-height: 1.2; text-transform: uppercase; font-family: 'Outfit', sans-serif;">
                                    Al Ihsan Da'awa College<br>
                                    <span style="font-size: 10.5px; font-weight: 700; color: #c5a85c; letter-spacing: 0px; text-transform: none; display: block; margin-top: 3px; line-height: 1.3;">
                                        Under Markaz Tha-aleemil Ihsan Muloor Udupi.<br>
                                        <span style="color: #1b365d; font-weight: 800; font-size: 9.5px;">Dakshina Karnataka Sunni Center (DKSC)</span>
                                    </span>
                                </span>
                            </a>
                        </div>
                        <div class="header__menu">
                            <div class="navigation">
                                <nav class="navigation__menu">
                                    <ul>
                                        <li class="navigation__menu--item">
                                            <a href="index.php" class="navigation__menu--item__link">Home</a>
                                        </li>
                                        <li class="navigation__menu--item has-child has-arrow">
                                            <a href="javascript:void(0);" class="navigation__menu--item__link">About</a>
                                            <ul class="submenu sub__style">
                                                <li><a href="about.php">About Us</a></li>
                                                <li><a href="about.php?p=principal">Office of Principal</a></li>
                                                <li><a href="about.php?p=adsa">Student's Association (ADSA)</a></li>
                                                <li><a href="about.php?p=dksc">DKSC Sunni Center</a></li>
                                            </ul>
                                        </li>
                                        <li class="navigation__menu--item has-child has-arrow">
                                            <a href="javascript:void(0);" class="navigation__menu--item__link">Academics</a>
                                            <ul class="submenu sub__style">
                                                <li><a href="program.php?p=bachelors-program">Bachelor's Programs</a></li>
                                                <li><a href="program.php?p=short-courses">Short-run Courses</a></li>
                                            </ul>
                                        </li>
                                        <li class="navigation__menu--item has-child has-arrow">
                                            <a href="javascript:void(0);" class="navigation__menu--item__link">Admission</a>
                                            <ul class="submenu sub__style">
                                                <li><a href="apply.php">Apply Now</a></li>
                                                <li><a href="admin.php">Admin Panel</a></li>
                                                <li><a href="about.php">Contact Us</a></li>
                                            </ul>
                                        </li>
                                        <li class="navigation__menu--item">
                                            <a href="events.php" class="navigation__menu--item__link">Events</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>

                        <!-- Hamburger Toggle Button -->
                        <button id="menu-btn" class="hamburger-toggle-btn" aria-label="Open Navigation Menu">
                            <i class="fa-solid fa-bars"></i>
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- header area end -->

    <!-- Mobile Responsive Offcanvas Sidebar -->
    <div id="side-bar" class="side-bar-container">
        <div class="side-bar-header">
            <div class="logo">
                <img src="assets/images/logo/logo_round.png?v=2" alt="Al Ihsan Logo" style="height: 40px; width: auto; object-fit: contain;">
                <span style="font-size: 13.5px; font-weight: 800; color: #1b365d; font-family: 'Outfit', sans-serif;">AL IHSAN</span>
            </div>
            <button class="close-icon-menu" aria-label="Close Navigation Menu">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="side-bar-content">
            <ul class="mobile-nav-list">
                <li>
                    <a href="index.php" class="mobile-nav-link">Home 🏡</a>
                </li>
                <li class="has-submenu">
                    <div class="submenu-header">
                        <a href="about.php" class="mobile-nav-link">About</a>
                        <button class="mobile-nav-link-toggle" aria-label="Toggle About Submenu">
                            <i class="fa-solid fa-chevron-down toggle-icon"></i>
                        </button>
                    </div>
                    <ul class="mobile-submenu">
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="about.php?p=principal">Office of Principal</a></li>
                        <li><a href="about.php?p=adsa">Student's Association (ADSA)</a></li>
                        <li><a href="about.php?p=dksc">DKSC Sunni Center</a></li>
                    </ul>
                </li>
                <li class="has-submenu">
                    <div class="submenu-header">
                        <a href="program.php" class="mobile-nav-link">Academics</a>
                        <button class="mobile-nav-link-toggle" aria-label="Toggle Academics Submenu">
                            <i class="fa-solid fa-chevron-down toggle-icon"></i>
                        </button>
                    </div>
                    <ul class="mobile-submenu">
                        <li><a href="program.php?p=bachelors-program">Bachelor's Programs</a></li>
                        <li><a href="program.php?p=short-courses">Short-run Courses</a></li>
                    </ul>
                </li>
                <li class="has-submenu">
                    <div class="submenu-header">
                        <a href="apply.php" class="mobile-nav-link">Admission</a>
                        <button class="mobile-nav-link-toggle" aria-label="Toggle Admission Submenu">
                            <i class="fa-solid fa-chevron-down toggle-icon"></i>
                        </button>
                    </div>
                    <ul class="mobile-submenu">
                        <li><a href="apply.php">Apply Now</a></li>
                        <li><a href="admin.php">Admin Panel</a></li>
                        <li><a href="about.php">Contact Us</a></li>
                    </ul>
                </li>
                <li>
                    <a href="events.php" class="mobile-nav-link">Events</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Mobile Side Menu Script (uses jQuery loaded in footer) -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function initMobileMenu() {
                if (typeof jQuery !== 'undefined') {
                    jQuery('#menu-btn').on('click', function(e) {
                        e.preventDefault();
                        jQuery('#side-bar').addClass('show');
                        jQuery('#anywhere-home').addClass('bgshow');
                    });
                    
                    jQuery('.close-icon-menu, #anywhere-home').on('click', function(e) {
                        e.preventDefault();
                        jQuery('#side-bar').removeClass('show');
                        jQuery('#anywhere-home').removeClass('bgshow');
                    });

                    jQuery('.mobile-nav-link-toggle').on('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        var submenu = jQuery(this).parent().siblings('.mobile-submenu');
                        submenu.slideToggle(250);
                        jQuery(this).find('.toggle-icon').toggleClass('fa-chevron-down fa-chevron-up');
                    });
                } else {
                    var menuBtn = document.getElementById('menu-btn');
                    var sideBar = document.getElementById('side-bar');
                    var anywhereHome = document.getElementById('anywhere-home');
                    var closeBtn = document.querySelector('.close-icon-menu');
                    
                    if (menuBtn && sideBar) {
                        menuBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            sideBar.classList.add('show');
                            if (anywhereHome) anywhereHome.classList.add('bgshow');
                        });
                    }
                    
                    var closeHandler = function(e) {
                        e.preventDefault();
                        sideBar.classList.remove('show');
                        if (anywhereHome) anywhereHome.classList.remove('bgshow');
                    };
                    
                    if (closeBtn) closeBtn.addEventListener('click', closeHandler);
                    if (anywhereHome) anywhereHome.addEventListener('click', closeHandler);
                    
                    var toggles = document.querySelectorAll('.mobile-nav-link-toggle');
                    toggles.forEach(function(toggle) {
                        toggle.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            var parentLi = this.closest('li');
                            var submenu = parentLi.querySelector('.mobile-submenu');
                            var icon = this.querySelector('.toggle-icon');
                            if (submenu) {
                                if (submenu.style.display === 'flex' || submenu.style.display === 'block') {
                                    submenu.style.display = 'none';
                                    icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
                                } else {
                                    submenu.style.display = 'block';
                                    icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
                                }
                            }
                        });
                    });
                }
            }
            initMobileMenu();
        });
    </script>

