<?php
error_reporting(E_ERROR);
ini_set('display_errors', 1);
include_once('security/classes/check.class.php');

$RandNumber = '?v='.rand(1,99999);

$dbname = GetClass('dbname');
$dbname = $dbname->getdbname();
function GetClass($MyClass,$dbname){
    if(class_exists($MyClass)){
        if($dbname){
            return new $MyClass($dbname);
        }else{
            return new $MyClass();
        }
    }else{
        include_once ('Server/'.$MyClass.'.php');
        if($dbname){
            return new $MyClass($dbname);
        }else{
            return new $MyClass();
        }
    }
}
?>
<!DOCTYPE html>
<!-- Template Name: Clip-One - Responsive Admin Template build with Twitter Bootstrap 3 Version: 1.0 Author: ClipTheme -->
<!--[if IE 8]><html class="ie8 no-js" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9 no-js" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
    <!--<![endif]-->
    <!-- start: HEAD -->
    <head>
        <title><?php echo $dbname;?></title>
        <!-- start: META -->
        <meta charset="utf-8" />
        <!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- end: META -->
        <!-- start: MAIN CSS -->
        <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link rel="stylesheet" href="assets/plugins/font-awesome/css/font-awesome.min.css<?php echo $RandNumber;?>">
        <link rel="stylesheet" href="assets/fonts/style.css<?php echo $RandNumber;?>">
        <link rel="stylesheet" href="assets/css/main.css<?php echo $RandNumber;?>">
        <link rel="stylesheet" href="assets/css/main-responsive.css<?php echo $RandNumber;?>">
        <link rel="stylesheet" href="assets/plugins/iCheck/skins/all.css<?php echo $RandNumber;?>">
        <link rel="stylesheet" href="assets/plugins/perfect-scrollbar/src/perfect-scrollbar.css<?php echo $RandNumber;?>">
        <link rel="stylesheet" href="assets/css/theme_black_and_white.css<?php echo $RandNumber;?>" id="skin_color">
        <!--[if IE 7]>
        <link rel="stylesheet" href="assets/plugins/font-awesome/css/font-awesome-ie7.min.css">
        <![endif]-->
        <!-- end: MAIN CSS -->
        <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
        <link rel="stylesheet" href="assets/plugins/fullcalendar/fullcalendar/fullcalendar.css<?php echo $RandNumber;?>">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js<?php echo $RandNumber;?>"></script>
        <!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
        <link rel="shortcut icon" href="favicon.ico" />
    </head>
    <!-- end: HEAD -->
    <!-- start: BODY -->
    <body>
        <!-- start: HEADER -->
        <div class="navbar navbar-inverse navbar-fixed-top">
            <!-- start: TOP NAVIGATION CONTAINER -->
            <div class="container">
                <div class="navbar-header">
                    <!-- start: RESPONSIVE MENU TOGGLER -->
                    <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
                        <span class="clip-list-2"></span>
                    </button>
                    <!-- end: RESPONSIVE MENU TOGGLER -->
                    <!-- start: LOGO -->
                    <a class="navbar-brand" href="index.html">
                        CLIP<i class="clip-clip"></i>ONE
                    </a>
                    <!-- end: LOGO -->
                </div>
                <div class="navbar-tools">
                    <!-- start: TOP NAVIGATION MENU -->
                    <ul class="nav navbar-right">
                        <!-- start: TO-DO DROPDOWN -->
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <i class="clip-list-5"></i>
                                <span class="badge"> 12</span>
                            </a>
                            <ul class="dropdown-menu todo">
                                <li>
                                    <span class="dropdown-menu-title"> You have 12 pending tasks</span>
                                </li>
                                <li>
                                    <div class="drop-down-wrapper">
                                        <ul>
                                            <li>
                                                <a class="todo-actions" href="javascript:void(0)">
                                                    <i class="icon-check-empty"></i>
                                                    <span class="desc" style="opacity: 1; text-decoration: none;">Staff Meeting</span>
                                                    <span class="label label-danger" style="opacity: 1;"> today</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="todo-actions" href="javascript:void(0)">
                                                    <i class="icon-check-empty"></i>
                                                    <span class="desc" style="opacity: 1; text-decoration: none;"> New frontend layout</span>
                                                    <span class="label label-danger" style="opacity: 1;"> today</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="todo-actions" href="javascript:void(0)">
                                                    <i class="icon-check-empty"></i>
                                                    <span class="desc"> Hire developers</span>
                                                    <span class="label label-warning"> tommorow</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="todo-actions" href="javascript:void(0)">
                                                    <i class="icon-check-empty"></i>
                                                    <span class="desc">Staff Meeting</span>
                                                    <span class="label label-warning"> tommorow</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="todo-actions" href="javascript:void(0)">
                                                    <i class="icon-check-empty"></i>
                                                    <span class="desc"> New frontend layout</span>
                                                    <span class="label label-success"> this week</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="todo-actions" href="javascript:void(0)">
                                                    <i class="icon-check-empty"></i>
                                                    <span class="desc"> Hire developers</span>
                                                    <span class="label label-success"> this week</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="todo-actions" href="javascript:void(0)">
                                                    <i class="icon-check-empty"></i>
                                                    <span class="desc"> New frontend layout</span>
                                                    <span class="label label-info"> this month</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="todo-actions" href="javascript:void(0)">
                                                    <i class="icon-check-empty"></i>
                                                    <span class="desc"> Hire developers</span>
                                                    <span class="label label-info"> this month</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="todo-actions" href="javascript:void(0)">
                                                    <i class="icon-check-empty"></i>
                                                    <span class="desc" style="opacity: 1; text-decoration: none;">Staff Meeting</span>
                                                    <span class="label label-danger" style="opacity: 1;"> today</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="todo-actions" href="javascript:void(0)">
                                                    <i class="icon-check-empty"></i>
                                                    <span class="desc" style="opacity: 1; text-decoration: none;"> New frontend layout</span>
                                                    <span class="label label-danger" style="opacity: 1;"> today</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="todo-actions" href="javascript:void(0)">
                                                    <i class="icon-check-empty"></i>
                                                    <span class="desc"> Hire developers</span>
                                                    <span class="label label-warning"> tommorow</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="view-all">
                                    <a href="javascript:void(0)">
                                        See all tasks <i class="icon-circle-arrow-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- end: TO-DO DROPDOWN-->
                        <!-- start: NOTIFICATION DROPDOWN -->
                        <li class="dropdown">
                            <a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" data-close-others="true" href="#">
                                <i class="clip-notification-2"></i>
                                <span class="badge"> 11</span>
                            </a>
                            <ul class="dropdown-menu notifications">
                                <li>
                                    <span class="dropdown-menu-title"> You have 11 notifications</span>
                                </li>
                                <li>
                                    <div class="drop-down-wrapper">
                                        <ul>
                                            <li>
                                                <a href="javascript:void(0)">
                                                    <span class="label label-primary"><i class="icon-user"></i></span>
                                                    <span class="message"> New user registration</span>
                                                    <span class="time"> 1 min</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)">
                                                    <span class="label label-success"><i class="icon-comment"></i></span>
                                                    <span class="message"> New comment</span>
                                                    <span class="time"> 7 min</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)">
                                                    <span class="label label-success"><i class="icon-comment"></i></span>
                                                    <span class="message"> New comment</span>
                                                    <span class="time"> 8 min</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)">
                                                    <span class="label label-success"><i class="icon-comment"></i></span>
                                                    <span class="message"> New comment</span>
                                                    <span class="time"> 16 min</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)">
                                                    <span class="label label-primary"><i class="icon-user"></i></span>
                                                    <span class="message"> New user registration</span>
                                                    <span class="time"> 36 min</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)">
                                                    <span class="label label-warning"><i class="icon-shopping-cart"></i></span>
                                                    <span class="message"> 2 items sold</span>
                                                    <span class="time"> 1 hour</span>
                                                </a>
                                            </li>
                                            <li class="warning">
                                                <a href="javascript:void(0)">
                                                    <span class="label label-danger"><i class="icon-user"></i></span>
                                                    <span class="message"> User deleted account</span>
                                                    <span class="time"> 2 hour</span>
                                                </a>
                                            </li>
                                            <li class="warning">
                                                <a href="javascript:void(0)">
                                                    <span class="label label-danger"><i class="icon-shopping-cart"></i></span>
                                                    <span class="message"> Transaction was canceled</span>
                                                    <span class="time"> 6 hour</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)">
                                                    <span class="label label-success"><i class="icon-comment"></i></span>
                                                    <span class="message"> New comment</span>
                                                    <span class="time"> yesterday</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)">
                                                    <span class="label label-primary"><i class="icon-user"></i></span>
                                                    <span class="message"> New user registration</span>
                                                    <span class="time"> yesterday</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)">
                                                    <span class="label label-primary"><i class="icon-user"></i></span>
                                                    <span class="message"> New user registration</span>
                                                    <span class="time"> yesterday</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)">
                                                    <span class="label label-success"><i class="icon-comment"></i></span>
                                                    <span class="message"> New comment</span>
                                                    <span class="time"> yesterday</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)">
                                                    <span class="label label-success"><i class="icon-comment"></i></span>
                                                    <span class="message"> New comment</span>
                                                    <span class="time"> yesterday</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="view-all">
                                    <a href="javascript:void(0)">
                                        See all notifications <i class="icon-circle-arrow-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- end: NOTIFICATION DROPDOWN -->
                        <!-- start: MESSAGE DROPDOWN -->
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-close-others="true" data-hover="dropdown" data-toggle="dropdown" href="#">
                                <i class="clip-bubble-3"></i>
                                <span class="badge"> 9</span>
                            </a>
                            <ul class="dropdown-menu posts">
                                <li>
                                    <span class="dropdown-menu-title"> You have 9 messages</span>
                                </li>
                                <li>
                                    <div class="drop-down-wrapper">
                                        <ul>
                                            <li>
                                                <a href="javascript:;">
                                                    <div class="clearfix">
                                                        <div class="thread-image">
                                                            <img alt="" src="./assets/images/avatar-2.jpg">
                                                        </div>
                                                        <div class="thread-content">
                                                            <span class="author">Nicole Bell</span>
                                                            <span class="preview">Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</span>
                                                            <span class="time"> Just Now</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <div class="clearfix">
                                                        <div class="thread-image">
                                                            <img alt="" src="./assets/images/avatar-1.jpg">
                                                        </div>
                                                        <div class="thread-content">
                                                            <span class="author">Peter Clark</span>
                                                            <span class="preview">Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</span>
                                                            <span class="time">2 mins</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <div class="clearfix">
                                                        <div class="thread-image">
                                                            <img alt="" src="./assets/images/avatar-3.jpg">
                                                        </div>
                                                        <div class="thread-content">
                                                            <span class="author">Steven Thompson</span>
                                                            <span class="preview">Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</span>
                                                            <span class="time">8 hrs</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <div class="clearfix">
                                                        <div class="thread-image">
                                                            <img alt="" src="./assets/images/avatar-1.jpg">
                                                        </div>
                                                        <div class="thread-content">
                                                            <span class="author">Peter Clark</span>
                                                            <span class="preview">Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</span>
                                                            <span class="time">9 hrs</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <div class="clearfix">
                                                        <div class="thread-image">
                                                            <img alt="" src="./assets/images/avatar-5.jpg">
                                                        </div>
                                                        <div class="thread-content">
                                                            <span class="author">Kenneth Ross</span>
                                                            <span class="preview">Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</span>
                                                            <span class="time">14 hrs</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="view-all">
                                    <a href="pages_messages.html">
                                        See all messages <i class="icon-circle-arrow-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- end: MESSAGE DROPDOWN -->
                        <!-- start: USER DROPDOWN -->
                        <li class="dropdown current-user">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <img src="assets/images/avatar-1-small.jpg" class="circle-img" alt="">
                                <span class="username">Peter Clark</span>
                                <i class="clip-chevron-down"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="pages_user_profile.html">
                                        <i class="clip-user-2"></i>
                                        &nbsp;My Profile
                                    </a>
                                </li>
                                <li>
                                    <a href="pages_calendar.html">
                                        <i class="clip-calendar"></i>
                                        &nbsp;My Calendar
                                    </a>
                                <li>
                                    <a href="pages_messages.html">
                                        <i class="clip-bubble-4"></i>
                                        &nbsp;My Messages (3)
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="utility_lock_screen.html"><i class="clip-locked"></i>
                                        &nbsp;Lock Screen </a>
                                </li>
                                <li>
                                    <a href="login_example1.html">
                                        <i class="clip-exit"></i>
                                        &nbsp;Log Out
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- end: USER DROPDOWN -->
                    </ul>
                    <!-- end: TOP NAVIGATION MENU -->
                </div>
            </div>
            <!-- end: TOP NAVIGATION CONTAINER -->
        </div>
        <!-- end: HEADER -->
        <!-- start: MAIN CONTAINER -->
        <div class="main-container">
            <div class="navbar-content">
                <!-- start: SIDEBAR -->
                <div class="main-navigation navbar-collapse collapse">
                    <!-- start: MAIN MENU TOGGLER BUTTON -->
                    <div class="navigation-toggler"></div>
                    <!-- end: MAIN MENU TOGGLER BUTTON -->
                    <!-- start: MAIN NAVIGATION MENU -->
                    <ul class="main-navigation-menu">
                        <li class="active open">
                            <a href="index.html"><i class="clip-home-3"></i>
                                <span class="title"> Dashboard </span><span class="selected"></span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><i class="clip-cog-2"></i>
                                <span class="title"> UI Lab </span><i class="icon-arrow"></i>
                                <span class="selected"></span>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="ui_elements.html">
                                        <span class="title"> Elements </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="ui_buttons.html">
                                        <span class="title"> Buttons &amp; icons </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="ui_modals.html">
                                        <span class="title"> Extended Modals </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="ui_tabs_accordions.html">
                                        <span class="title"> Tabs &amp; Accordions </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="ui_sliders.html">
                                        <span class="title"> Sliders </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="ui_treeview.html">
                                        <span class="title"> Treeview </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="ui_nestable.html">
                                        <span class="title"> Nestable List </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="ui_typography.html">
                                        <span class="title"> Typography </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><i class="clip-grid-6"></i>
                                <span class="title"> Tables </span><i class="icon-arrow"></i>
                                <span class="selected"></span>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="table_static.html">
                                        <span class="title">Static Tables</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="table_responsive.html">
                                        <span class="title">Responsive Tables</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="table_data.html">
                                        <span class="title">Data Tables</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><i class="clip-pencil"></i>
                                <span class="title"> Forms </span><i class="icon-arrow"></i>
                                <span class="selected"></span>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="form_elements.html">
                                        <span class="title">Form Elements</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="form_wizard.html">
                                        <span class="title">Form Wizard</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="form_validation.html">
                                        <span class="title">Form Validation</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="form_inline.html">
                                        <span class="title">Inline Editor</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="form_image_cropping.html">
                                        <span class="title">Image Cropping</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="form_multiple_upload.html">
                                        <span class="title">Multiple File Upload</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="form_dropzone.html">
                                        <span class="title">Dropzone File Upload</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><i class="clip-user-2"></i>
                                <span class="title">Login</span><i class="icon-arrow"></i>
                                <span class="selected"></span>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="login_example1.html">
                                        <span class="title">Login Form Example 1</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="login_example2.html">
                                        <span class="title">Login Form Example 2</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><i class="clip-file"></i>
                                <span class="title">Pages</span><i class="icon-arrow"></i>
                                <span class="selected"></span>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="pages_timeline.html">
                                        <span class="title">Timeline</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="pages_calendar.html">
                                        <span class="title">Calendar</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="pages_messages.html">
                                        <span class="title">Messages</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="pages_blank_page.html">
                                        <span class="title">Blank Page</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><i class="clip-attachment-2"></i>
                                <span class="title">Utility</span><i class="icon-arrow"></i>
                                <span class="selected"></span>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="utility_faq.html">
                                        <span class="title">Faq</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="utility_lock_screen.html">
                                        <span class="title">Lock Screen</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="utility_404_example1.html">
                                        <span class="title">Error 404 Example 1</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="utility_404_example2.html">
                                        <span class="title">Error 404 Example 2</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="utility_404_example3.html">
                                        <span class="title">Error 404 Example 3</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="utility_500_example1.html">
                                        <span class="title">Error 500 Example 1</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="utility_500_example2.html">
                                        <span class="title">Error 500 Example 2</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="utility_pricing_table.html">
                                        <span class="title">Pricing Table</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="utility_coming_soon.html">
                                        <span class="title">Cooming Soon</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:;" class="active">
                                <i class="clip-folder"></i>
                                <span class="title"> 3 Level Menu </span>
                                <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="javascript:;">
                                        Item 1 <i class="icon-arrow"></i>
                                    </a>
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="#">
                                                Sample Link 1
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 2
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 3
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        Item 1 <i class="icon-arrow"></i>
                                    </a>
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="#">
                                                Sample Link 1
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 1
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 1
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#">
                                        Item 3
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:;">
                                <i class="clip-folder-open"></i>
                                <span class="title"> 4 Level Menu </span><i class="icon-arrow"></i>
                                <span class="arrow "></span>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="javascript:;">
                                        Item 1 <i class="icon-arrow"></i>
                                    </a>
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="javascript:;">
                                                Sample Link 1 <i class="icon-arrow"></i>
                                            </a>
                                            <ul class="sub-menu">
                                                <li>
                                                    <a href="#"><i class="icon-remove"></i>
                                                        Sample Link 1</a>
                                                </li>
                                                <li>
                                                    <a href="#"><i class="icon-pencil"></i>
                                                        Sample Link 1</a>
                                                </li>
                                                <li>
                                                    <a href="#"><i class="icon-edit"></i>
                                                        Sample Link 1</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 1
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 2
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 3
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        Item 2 <i class="icon-arrow"></i>
                                    </a>
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="#">
                                                Sample Link 1
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 1
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 1
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#">
                                        Item 3
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="maps.html"><i class="clip-location"></i>
                                <span class="title">Maps</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li>
                            <a href="charts.html"><i class="clip-bars"></i>
                                <span class="title">Charts</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                    </ul>
                    <!-- end: MAIN NAVIGATION MENU -->
                </div>
                <!-- end: SIDEBAR -->
            </div>
            <!-- start: PAGE -->
