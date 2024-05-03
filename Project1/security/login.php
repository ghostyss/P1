<!DOCTYPE html>
<?php if (!isset($_SESSION)) session_start(); ?>
<?php include_once('classes/login.class.php');
include_once ('../Server/dbname.php');
include_once ('../Server/general_config.php');
$m = new dbname();
$dbname = $m->getdbname();
$general_config_obj = new general_config($dbname);
$idgeneral_config = 1;
$general_config = $general_config_obj->getgeneral_configById($idgeneral_config);
if ($general_config->getlogo1() && $general_config->getlogo1() != 'no') {
    $pathlogo = '../temp/Logo' . $dbname . '.png';
    $fh = fopen($pathlogo, 'w') or die("can't open file");
    $stringData = $general_config->getlogo1();
    fwrite($fh, $stringData);
} else {
    $pathlogo = '../images/logo.png';
}

?>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | <?php echo $dbname;?></title>
    <link href="../css/styles.css" rel="stylesheet" type="text/css">
    <link href="../css/styles-grey.css" rel="stylesheet" type="text/css">
    <link rel="shortcut icon" type="image/x-icon" href="../favicon.ico" />
</head>

<body>
    <div id="e-loader-overlay" style="opacity: 0.2;display: none;width:100%;height:100%;position:fixed;background-color:black;z-index:10001"></div>
    <div id="e-loader-img" style="left: 50%; top: 50%; margin-left: -26px; margin-top: -26px;display: none;position:fixed;z-index:10002">
        <img id="loader" src="../images/loader6.gif" />
    </div>
    <div class="colorful-page-wrapper">
        <div class="center-block">
            <div class="login-block lbh">
                <form action="login.php" method="post" id="login-form" name="login" class="orb-form">
                    <header>
                        <div class="image-block"><img src="<?php echo $pathlogo; ?>"  onerror="javascript:$(this).attr('src','../images/logo.png');$(this).addClass('NewStyleLogo')" alt="<?php echo $dbname;?>" /></div>
                        Login to <?php echo $dbname;?> 
                        <small style="margin-bottom: 3%;">Have no account? &#8212; <a href="https://titlehost.com/contact.php" target="_blank">Register</a></small>
                    </header>
                    <fieldset>
                        <section>
                            <div class="row">
                                <label class="label col col-4">Username</label>
                                <div class="col col-8">
                                    <label class="input"> <i class="icon-append fa fa-user"></i>
                                        <input type="text" name="username" id="username">
                                    </label>
                                </div>
                            </div>
                        </section>
                        <section>
                            <div class="row">
                                <label class="label col col-4">Password</label>
                                <div class="col col-8">
                                    <label class="input"> <i class="icon-append fa fa-lock"></i>
                                        <input type="password" name="password" id="password">
                                    </label>
                                    <div class="note"><a href="javascript:void(0);" class="forgotpass">Forgot password?</a></div>
                                </div>
                            </div>
                        </section>
                        <section>
                            <div class="row">
                                <div class="col col-4"></div>
                                <div class="col col-8">
                                    <label class="checkbox">
                                        <input type="checkbox" name="remember" checked>
                                        <i></i>Keep me logged in</label>
                                </div>
                            </div>
                        </section>
                    </fieldset>
                    <footer>
                        <button type="submit" class="btn btn-default">Log in</button>
                    </footer>
                    <input type="hidden" name="token" value="<?php echo $_SESSION['jigowatt']['token']; ?>"/>
                    <?php if (!empty($jigowatt_integration->enabledMethods)) : ?>
                        <div class="">
                            <?php foreach ($jigowatt_integration->enabledMethods as $key) : ?>
                                <p><a href="login.php?login=<?php echo $key; ?>"><img src="assets/img/<?php echo $key; ?>_signin.png"></a></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
            <div class="login-block hide rbh">
                <form action="login.php" method="post" id="recovery-form" class="orb-form">
                    <header>
                        <div class="image-block"><img src="<?php echo $pathlogo; ?>" onerror="javascript:$(this).attr('src','../images/logo.png');$(this).addClass('NewStyleLogo')" alt="<?php echo $dbname; ?>" /></div>
                        Recovery Password to <?php echo $dbname; ?> 
                        <small style="margin-bottom: 3%;">Have no account? &#8212; <a href="https://titlehost.com/contact.php" target="_blank">Register</a></small>
                    </header>
                    <fieldset>
                        <section>
                            <div class="row">
                                <label class="label col col-4">Username</label>
                                <div class="col col-8">
                                    <label class="input"> <i class="icon-append fa fa-user"></i>
                                        <input type="text" name="username2" id="username2">
                                    </label>
                                    <div class="note"><a href="javascript:void(0);" class="returnlogin">Return to Login</a></div>
                                </div>
                            </div>
                        </section>
                        <section>
                            <div class="row">
                                <div class="col col-8">

                                </div>
                            </div>
                        </section>
                    </fieldset>
                    <footer>
                        <label class="btn btn-default recoveribtn">Recovery Password</label>
                    </footer>
                    <input type="hidden" name="token" value="<?php echo $_SESSION['jigowatt']['token']; ?>"/>
                    <?php if (!empty($jigowatt_integration->enabledMethods)) : ?>
                        <div class="">
                            <?php foreach ($jigowatt_integration->enabledMethods as $key) : ?>
                                <p><a href="login.php?login=<?php echo $key; ?>"><img src="assets/img/<?php echo $key; ?>_signin.png"></a></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
            <div class="copyrights"> TitleHost <br>
                Copyrigths &copy; <?php echo date('Y');?> </div>
        </div>
    </div>

    <!--Scripts--> 
    <!--JQuery--> 
    <script type="text/javascript" src="../js/vendors/jquery/jquery.min.js"></script> 
    <script type="text/javascript" src="../js/vendors/jquery/jquery-ui.min.js"></script> 

    <!--Forms--> 
    <script type="text/javascript" src="../js/vendors/forms/jquery.form.min.js"></script> 
    <script type="text/javascript" src="../js/vendors/forms/jquery.validate.min.js"></script> 
    <script type="text/javascript" src="../js/vendors/forms/jquery.maskedinput.min.js"></script> 
    <script type="text/javascript" src="../js/vendors/jquery-steps/jquery.steps.min.js"></script> 

    <!--NanoScroller--> 
    <script type="text/javascript" src="../js/vendors/nanoscroller/jquery.nanoscroller.min.js"></script> 

    <!--Sparkline--> 
    <script type="text/javascript" src="../js/vendors/sparkline/jquery.sparkline.min.js"></script> 

    <!--Main App--> 
    <!--<script type="text/javascript" src="js/scripts.js"></script>-->
    <script type="text/javascript" src="../js/vendors/modernizr/modernizr.custom.js"></script>
    <script type="text/javascript" src="../js/vendors/horisontal/cbpHorizontalSlideOutMenu.js"></script> 
    <script type="text/javascript" src="../js/vendors/classie/classie.js"></script>
    <script type="text/javascript" src="../js/vendors/powerwidgets/powerwidgets.min.js"></script>
    <script type="text/javascript" src="../js/jsend.min.js"></script>
    <script type="text/javascript" src="../js/e_notify.1.0.js"></script>
    <link href="../css/enotify.css?v=<?php echo rand(1, 999); ?>" rel="stylesheet" type="text/css">


    <!--/Scripts-->
    <style>
        .center-block .copyrights{
            color: black;
        }
        .note{
            text-align: right;
        }
        .hide{
            display:none;
        }
        /**/
        input:read-only{
            background-color: EAE8E8 !important;
            cursor: not-allowed;
        }
        .e-growl-right-top{
            z-index: 999999 !important;
        }
        body .custom-blue{
            background-color : #097299;/* fallback color */
            background-image : -webkit-gradient(linear, left top, left bottom, from(#10b5f2), to(#097299));/* Safari 4+, Chrome 1+ */
            background-image : -webkit-linear-gradient(top, #10b5f2, #097299);/* Chrome 10+, Saf5.1+, iOS 5+ */
            background-image :    -moz-linear-gradient(top, #10b5f2, #097299);/* Firefox 3.6+ */
            background-image :     -ms-linear-gradient(top, #10b5f2, #097299); /* IE10 */
            background-image :      -o-linear-gradient(top, #10b5f2, #097299); /* Opera 11.10+ */
            background-image :         linear-gradient(to bottom, #10b5f2, #097299);
            -ms-filter       : "progid : DXImageTransform.Microsoft.gradient (GradientType=0, startColorstr=#10b5f2, endColorstr=#097299)";/* IE8 */
            color            : #fff;
            border           : 1px solid #003548;	
        }
        body .custom-blue:hover{
            background-color : #075d7d;/* fallback color */
            background-image : -webkit-gradient(linear, left top, left bottom, from(#0d9fd5), to(#075d7d));/* Safari 4+, Chrome 1+ */
            background-image : -webkit-linear-gradient(top, #0d9fd5, #075d7d);/* Chrome 10+, Saf5.1+, iOS 5+ */
            background-image :    -moz-linear-gradient(top, #0d9fd5, #075d7d);/* Firefox 3.6+ */
            background-image :     -ms-linear-gradient(top, #0d9fd5, #075d7d); /* IE10 */
            background-image :      -o-linear-gradient(top, #0d9fd5, #075d7d); /* Opera 11.10+ */
            background-image :         linear-gradient(to bottom, #0d9fd5, #075d7d);
            -ms-filter       : "progid : DXImageTransform.Microsoft.gradient (GradientType=0, startColorstr=#0d9fd5, endColorstr=#075d7d)";/* IE8 */
            color            : #fff;
            border           : 1px solid #022430;	
        }
        body .custom-green{
            background-color : #10750e;/* fallback color */
            background-image : -webkit-gradient(linear, left top, left bottom, from(#0ead0a), to(#10750e));/* Safari 4+, Chrome 1+ */
            background-image : -webkit-linear-gradient(top, #0ead0a, #10750e);/* Chrome 10+, Saf5.1+, iOS 5+ */
            background-image :    -moz-linear-gradient(top, #0ead0a, #10750e);/* Firefox 3.6+ */
            background-image :     -ms-linear-gradient(top, #0ead0a, #10750e); /* IE10 */
            background-image :      -o-linear-gradient(top, #0ead0a, #10750e); /* Opera 11.10+ */
            background-image :         linear-gradient(to bottom, #0ead0a, #10750e);
            -ms-filter       : "progid : DXImageTransform.Microsoft.gradient (GradientType=0, startColorstr=#0ead0a, endColorstr=#10750e)";/* IE8 */
            color            : #fff;
            border           : 1px solid #074506;	
        }
        body .custom-green:hover{
            background-color : #0a5d08;/* fallback color */
            background-image : -webkit-gradient(linear, left top, left bottom, from(#0b8e07), to(#0a5d08));/* Safari 4+, Chrome 1+ */
            background-image : -webkit-linear-gradient(top, #0b8e07, #0a5d08);/* Chrome 10+, Saf5.1+, iOS 5+ */
            background-image :    -moz-linear-gradient(top, #0b8e07, #0a5d08);/* Firefox 3.6+ */
            background-image :     -ms-linear-gradient(top, #0b8e07, #0a5d08); /* IE10 */
            background-image :      -o-linear-gradient(top, #0b8e07, #0a5d08); /* Opera 11.10+ */
            background-image :         linear-gradient(to bottom, #0b8e07, #0a5d08);
            -ms-filter       : "progid : DXImageTransform.Microsoft.gradient (GradientType=0, startColorstr=#0b8e07, endColorstr=#0a5d08)";/* IE8 */
            color            : #fff;
            border           : 1px solid #053705;	
        }
        body .custom-red{
            background-color : #9c1313;/* fallback color */
            background-image : -webkit-gradient(linear, left top, left bottom, from(#d71b1b), to(#9c1313));/* Safari 4+, Chrome 1+ */
            background-image : -webkit-linear-gradient(top, #d71b1b, #9c1313);/* Chrome 10+, Saf5.1+, iOS 5+ */
            background-image :    -moz-linear-gradient(top, #d71b1b, #9c1313);/* Firefox 3.6+ */
            background-image :     -ms-linear-gradient(top, #d71b1b, #9c1313); /* IE10 */
            background-image :      -o-linear-gradient(top, #d71b1b, #9c1313); /* Opera 11.10+ */
            background-image :         linear-gradient(to bottom, #d71b1b, #9c1313);
            -ms-filter       : "progid : DXImageTransform.Microsoft.gradient (GradientType=0, startColorstr=#d71b1b, endColorstr=#9c1313)";/* IE8 */
            color            : #fff;
            border           : 1px solid #4a0606;		
        }
        body .custom-red:hover{
            background-color : #840d0d;/* fallback color */
            background-image : -webkit-gradient(linear, left top, left bottom, from(#ae1212), to(#840d0d));/* Safari 4+, Chrome 1+ */
            background-image : -webkit-linear-gradient(top, #ae1212, #840d0d);/* Chrome 10+, Saf5.1+, iOS 5+ */
            background-image :    -moz-linear-gradient(top, #ae1212, #840d0d);/* Firefox 3.6+ */
            background-image :     -ms-linear-gradient(top, #ae1212, #840d0d); /* IE10 */
            background-image :      -o-linear-gradient(top, #ae1212, #840d0d); /* Opera 11.10+ */
            background-image :         linear-gradient(to bottom, #ae1212, #840d0d);
            -ms-filter       : "progid : DXImageTransform.Microsoft.gradient (GradientType=0, startColorstr=#ae1212, endColorstr=#840d0d)";/* IE8 */
            color            : #fff;
            border           : 1px solid #310303;		
        }
        body .custom-purple{
            background-color : #7c1b7b;/* fallback color */
            background-image : -webkit-gradient(linear, left top, left bottom, from(#d931d7), to(#7c1b7b));/* Safari 4+, Chrome 1+ */
            background-image : -webkit-linear-gradient(top, #d931d7, #7c1b7b);/* Chrome 10+, Saf5.1+, iOS 5+ */
            background-image :    -moz-linear-gradient(top, #d931d7, #7c1b7b);/* Firefox 3.6+ */
            background-image :     -ms-linear-gradient(top, #d931d7, #7c1b7b); /* IE10 */
            background-image :      -o-linear-gradient(top, #d931d7, #7c1b7b); /* Opera 11.10+ */
            background-image :         linear-gradient(to bottom, #d931d7, #7c1b7b);
            -ms-filter       : "progid : DXImageTransform.Microsoft.gradient (GradientType=0, startColorstr=#d931d7, endColorstr=#7c1b7b)";/* IE8 */
            color            : #fff;
            border           : 1px solid #370636;
        }
        body .custom-purple:hover{
            background-color : #611260;/* fallback color */
            background-image : -webkit-gradient(linear, left top, left bottom, from(#a91ca7), to(#611260));/* Safari 4+, Chrome 1+ */
            background-image : -webkit-linear-gradient(top, #a91ca7, #611260);/* Chrome 10+, Saf5.1+, iOS 5+ */
            background-image :    -moz-linear-gradient(top, #a91ca7, #611260);/* Firefox 3.6+ */
            background-image :     -ms-linear-gradient(top, #a91ca7, #611260); /* IE10 */
            background-image :      -o-linear-gradient(top, #a91ca7, #611260); /* Opera 11.10+ */
            background-image :         linear-gradient(to bottom, #a91ca7, #611260);
            -ms-filter       : "progid : DXImageTransform.Microsoft.gradient (GradientType=0, startColorstr=#a91ca7, endColorstr=#611260)";/* IE8 */
            color            : #fff;
            border           : 1px solid #280427;
        }
        body .custom-orange{
            background-color : #c33712;/* fallback color */
            background-image : -webkit-gradient(linear, left top, left bottom, from(#ff4d1d), to(#c33712));/* Safari 4+, Chrome 1+ */
            background-image : -webkit-linear-gradient(top, #ff4d1d, #c33712);/* Chrome 10+, Saf5.1+, iOS 5+ */
            background-image :    -moz-linear-gradient(top, #ff4d1d, #c33712);/* Firefox 3.6+ */
            background-image :     -ms-linear-gradient(top, #ff4d1d, #c33712); /* IE10 */
            background-image :      -o-linear-gradient(top, #ff4d1d, #c33712); /* Opera 11.10+ */
            background-image :         linear-gradient(to bottom, #ff4d1d, #c33712);
            -ms-filter       : "progid : DXImageTransform.Microsoft.gradient (GradientType=0, startColorstr=#ff4d1d, endColorstr=#c33712)";/* IE8 */
            color            : #fff;
            border           : 1px solid #4c1304;	
        }
        body .custom-orange:hover{
            background-color : #9c290a;/* fallback color */
            background-image : -webkit-gradient(linear, left top, left bottom, from(#dc390d), to(#9c290a));/* Safari 4+, Chrome 1+ */
            background-image : -webkit-linear-gradient(top, #dc390d, #9c290a);/* Chrome 10+, Saf5.1+, iOS 5+ */
            background-image :    -moz-linear-gradient(top, #dc390d, #9c290a);/* Firefox 3.6+ */
            background-image :     -ms-linear-gradient(top, #dc390d, #9c290a); /* IE10 */
            background-image :      -o-linear-gradient(top, #dc390d, #9c290a); /* Opera 11.10+ */
            background-image :         linear-gradient(to bottom, #dc390d, #9c290a);
            -ms-filter       : "progid : DXImageTransform.Microsoft.gradient (GradientType=0, startColorstr=#dc390d, endColorstr=#9c290a)";/* IE8 */
            color            : #fff;
            border           : 1px solid #2d0b02;	
        }
        body .custom-black{
            background-color : #111111;/* fallback color */
            background-image : -webkit-gradient(linear, left top, left bottom, from(#333333), to(#111111));/* Safari 4+, Chrome 1+ */
            background-image : -webkit-linear-gradient(top, #333333, #111111);/* Chrome 10+, Saf5.1+, iOS 5+ */
            background-image :    -moz-linear-gradient(top, #333333, #111111);/* Firefox 3.6+ */
            background-image :     -ms-linear-gradient(top, #333333, #111111); /* IE10 */
            background-image :      -o-linear-gradient(top, #333333, #111111); /* Opera 11.10+ */
            background-image :         linear-gradient(to bottom, #333333, #111111);
            -ms-filter       : "progid : DXImageTransform.Microsoft.gradient (GradientType=0, startColorstr=#333333, endColorstr=#111111)";/* IE8 */
            color            : #fff;
            border           : 1px solid #000000;
        }
        body .custom-black:hover{
            background-color : #000000;/* fallback color */
            background-image : -webkit-gradient(linear, left top, left bottom, from(#222222), to(#000000));/* Safari 4+, Chrome 1+ */
            background-image : -webkit-linear-gradient(top, #222222, #000000);/* Chrome 10+, Saf5.1+, iOS 5+ */
            background-image :    -moz-linear-gradient(top, #222222, #000000);/* Firefox 3.6+ */
            background-image :     -ms-linear-gradient(top, #222222, #000000); /* IE10 */
            background-image :      -o-linear-gradient(top, #222222, #000000); /* Opera 11.10+ */
            background-image :         linear-gradient(to bottom, #222222, #000000);
            -ms-filter       : "progid : DXImageTransform.Microsoft.gradient (GradientType=0, startColorstr=#222222, endColorstr=#000000)";/* IE8 */
            color            : #fff;
            border           : 1px solid #000000;
        }
        body .custom-grey{
            background-color : #666666;/* fallback color */
            background-image : -webkit-gradient(linear, left top, left bottom, from(#999999), to(#666666));/* Safari 4+, Chrome 1+ */
            background-image : -webkit-linear-gradient(top, #999999, #666666);/* Chrome 10+, Saf5.1+, iOS 5+ */
            background-image :    -moz-linear-gradient(top, #999999, #666666);/* Firefox 3.6+ */
            background-image :     -ms-linear-gradient(top, #999999, #666666); /* IE10 */
            background-image :      -o-linear-gradient(top, #999999, #666666); /* Opera 11.10+ */
            background-image :         linear-gradient(to bottom, #999999, #666666);
            -ms-filter       : "progid : DXImageTransform.Microsoft.gradient (GradientType=0, startColorstr=#999999, endColorstr=#666666)";/* IE8 */
            color            : #fff;
            border           : 1px solid #222222;	
        }
        body .custom-grey:hover{
            background-color : #555555;/* fallback color */
            background-image : -webkit-gradient(linear, left top, left bottom, from(#777777), to(#555555));/* Safari 4+, Chrome 1+ */
            background-image : -webkit-linear-gradient(top, #777777, #555555);/* Chrome 10+, Saf5.1+, iOS 5+ */
            background-image :    -moz-linear-gradient(top, #777777, #555555);/* Firefox 3.6+ */
            background-image :     -ms-linear-gradient(top, #777777, #555555); /* IE10 */
            background-image :      -o-linear-gradient(top, #777777, #555555); /* Opera 11.10+ */
            background-image :         linear-gradient(to bottom, #777777, #555555);
            -ms-filter       : "progid : DXImageTransform.Microsoft.gradient (GradientType=0, startColorstr=#777777, endColorstr=#555555)";/* IE8 */
            color            : #fff;
            border           : 1px solid #111111;	
        }
        .growl-default{
            background-color : rgb(0,0,0);/* fallback */
            background-color : rgba(0,0,0,0.9);
            color            : #fff;
        }
        .growl-white{
            background-color : rgb(255,255,255);/* fallback */
            background-color : rgba(255,255,255,0.9);
            color            : #000;
        }
        /**/
    </style>
    <script>//console.log($('.alert-error').text());
        var poner = '<div class="row"><div class="col col-md-12"><span class="alert alert-danger col col-md-12" style="font-size:1.2rem;text-align:center;">' + $('.alert-error').text() + '</span></div></div>';
        if ($('.alert-error').text() != '') {
            $('#login-form').children().first().append(poner);
        }
        var intervalclear = '';
        function notification_bar(msg, type) {
            if (type) {
                var image = 'success.png';
                var classe = 'custom-green';
                var stikable = false;
            } else {
                var image = 'Alert.jpeg';
                var classe = 'custom-red';
                var stikable = true;
            }
            $.e_notify.growl({
                title: 'System Message',
                text: msg,
                image: '../images/' + image,
                position: 'right-top',
                delay: 0,
                type: 'danger',
                time: 5000,
                speed: 400,
                effect: 'slidein',
                sticky: stikable,
                closable: stikable,
                maxOpen: 15,
                className: classe,
                onShow: function () {},
                onHide: function () {}
            });
        }
        $.fn.serializeObject = function () {
            var o = {};
            var a = this.serializeArray();
            $.each(a, function () {
                if (o[this.name] !== undefined) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(this.value || '');
                } else {
                    o[this.name] = this.value || '';
                }
            });
            return o;
        };
        function showLoading(show, clear) {
            var show = show || false;
            if (show) {
                $('#e-loader-overlay').show();
                $('#e-loader-img').show();
                if (clear != 'no') {
                    clearInterval(intervalclear);
                    intervalclear = setInterval(function () {
                        showLoading(false);
                    }, 20000);
                }
            } else {
                $('#e-loader-overlay').hide();
                $('#e-loader-img').hide();
                clearInterval(intervalclear);
            }
        }
        function PostPsToDB(adata, op, rqt) {
            rqt = rqt || 'No request value';
            var URL = '../pstodbFinal.php';
            adata = "input=" + op + adata + "&action=result";
            $.ajax({
                type: "POST",
                data: adata,
                url: URL,
                success: function (data) {
                    data = $.trim(data);
                    showLoading(false);
                    if (data.indexOf('Error') == '-1' || data.indexOf('Error') > 15) {
                        if (op == '54') {
                            showLoading(false);
                            data = $.trim(data);
                            notification_bar(data, true);
                        }
                    } else {
                        if (op == '08') {
                            UpdateTpanelInf($('.idtransactionajax').val());
                        }
                        notification_bar(data, false, '');
                        ReportError('pstodbFinal', op, URL, rqt, data);
                    }
                },
                error: function (data) {
                    showLoading(false);
                    reportError('pstodbFinal', op, URL, rqt, data);
                }
            });
        }
        $(document).ready(function () {
            $('.forgotpass').on('click', function () {
                $('.lbh').addClass('hide');
                $('.rbh').removeClass('hide');
            });
            $('.returnlogin').on('click', function () {
                $('.rbh').addClass('hide');
                $('.lbh').removeClass('hide');
            });
            $('.recoveribtn').off();
            $('.recoveribtn').on('click', function () {
                if ($('input[name="username2"]').val()) {
                    var a = JSON.stringify($('#recovery-form').serializeObject());//console.log(a);
                    var msj = $.jSEND(a);
                    showLoading(true);
                    PostPsToDB(msj, '54');
                } else {
                    notification_bar('Please Fill Username Field');
                }
            });
        });
    </script>
</body>
</html>