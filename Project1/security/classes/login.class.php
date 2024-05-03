<?php

/**
 * Verify and execute the login process.
 *
 * LICENSE:
 *
 * This source file is subject to the licensing terms that
 * is available through the world-wide-web at the following URI:
 * http://codecanyon.net/wiki/support/legal-terms/licensing-terms/.
 *
 * @author       Jigowatt <info@jigowatt.co.uk>
 * @author       Matt Gates <matt.gates@jigoshop.com>
 * @copyright    Copyright © 2009-2012 Jigowatt Ltd.
 * @license      http://codecanyon.net/wiki/support/legal-terms/licensing-terms/
 * @link         http://codecanyon.net/item/php-login-user-management/49008
 */
include_once( 'integration.class.php' );

class Login extends jigowatt_integration {

    // Post vars
    private $user;
    private $pass;
    public $use_emails = false;
    // Misc vars
    private $token;
    private $valid;
    private $result;
    private $error;
    private $msg;

    function __construct() {

        // Disable users from logging in?
        if (parent::getOption('disable-logins-enable')) {
            parent::displayMessage('<div class="row"><div class="col col-md-12"><span class="alert alert-danger col col-md-12" style="font-size:1.2rem;text-align:center;">' . _('The admin has disabled logins.') . '</span></div></div>');
        }

        $this->use_emails = parent::getOption('email-as-username-enable');
        $this->username_type = ( $this->use_emails ) ? 'email' : 'username';

        // Redirect the logging in user
        if (parent::getOption('signin-redirect-referrer-enable'))
            $_SESSION['jigowatt']['referer'] = (!empty($_SESSION['jigowatt']['referer'])) ? $_SESSION['jigowatt']['referer'] : '../index.php';
        else
            $_SESSION['jigowatt']['referer'] = parent::getOption('signin-redirect-url');

        // Are they attempting to access a secure page?
        $this->isSecure();
        
        // Only allow guests to view this page
        parent::guestOnly();

        // Generate a unique token for security purposes
        parent::generateToken();

        // Login form post data
        if (isset($_POST['login'])) :
            $this->user = parent::secure($_POST['username']);
            $this->pass = parent::secure($_POST['password']);

            $this->token = !empty($_POST['token']) ? $_POST['token'] : '';
            $this->process();
        endif;
        
        if (!empty($_GET['login']) || !empty($_GET['link']))
            !empty($_GET['link']) ? parent::link_account($_GET['link'], true) : parent::link_account($_GET['login'], true);

        foreach (parent::$socialLogin as $provider) :
            if (!empty($_SESSION['jigowatt'][$provider])) {
                $this->social_login($provider);
                break;
            }
        endforeach;
        //echo 5;
        // Display the errors and do not exit the page
        //print_r($this->msg);
        $this->error ? parent::displayMessage($this->error, false) : parent::displayMessage($this->msg, false);
    }

    private function social_login($provider) {

        $params = array(':session' => $_SESSION['jigowatt'][$provider]);
        $stmt = parent::query("SELECT `user_id` FROM `login_integration` WHERE `$provider` = :session;", $params);

        if ($stmt->rowCount() > 0) {

            $result = $stmt->fetch();

            $params = array(':user_id' => $result['user_id']);
            $stmt = parent::query("SELECT * FROM `login_users` WHERE `user_id` = :user_id;", $params);

            $this->result = $stmt->fetch();

            $username = $this->username_type;
            $this->user = $this->result[$username];

            $this->login();
        } else {

            unset(
                    $_SESSION['jigowatt']['ot'],
                    $_SESSION['jigowatt']['ots'],
                    $_SESSION['jigowatt'][$provider]
            );

            header('Location: sign_up.php?new_social');
            exit();
        }
    }

    private function isSecure() {

        if (isset($_GET['e'])) :
            if (parent::getOption('block-msg-out-enable'))
                $this->msg = '<div class="row"><div class="col col-md-12"><span class="alert alert-danger col col-md-12" style="font-size:1.2rem;text-align:center;">' . parent::getOption('block-msg-out') . '</span></div></div>';
        endif;
    }

    private function process() {

        // Check that the token is valid, prevents exploits
        if (!parent::valid_token($this->token)) {
            $this->error = '<div class="row"><div class="col col-md-12"><span class="alert alert-danger col col-md-12" style="font-size:1.2rem;text-align:center;">' . _('Invalid login attempt') . '</span></div></div>';
            return false;
        }

        // Confirm all details are correct
        $this->validate();

        // Log the user in
        $this->login();
    }

    private function validate() {

        if (!empty($this->error))
            return false;

        if (empty($this->user)) {
            $this->error = '<div class="row"><div class="col col-md-12"><span class="alert alert-danger col col-md-12" style="font-size:1.2rem;text-align:center;">' . (( $this->use_emails ) ? _('You must enter an email address.') : _('You must enter a username.')) . '</span></div></div>';
            return false;
        }

        if (empty($this->pass)) {
            $this->error = '<div class="row"><div class="col col-md-12"><span class="alert alert-danger col col-md-12" style="font-size:1.2rem;text-align:center;">' . _('You forgot your password, silly.') . '</span></div></div>';
            return false;
        }

        $username = $this->username_type;
        $params = array('username' => $this->user);
        $stmt = parent::query("SELECT * FROM login_users WHERE {$username} = :username", $params);

        $this->result = $stmt->fetch();

        if (!parent::validatePassword($this->pass, $this->result['password'])) {

            $username = $this->username_type;
            $this->error = "<div class=\"alert alert-error\">" . sprintf(_('Incorrect %s or password.'), $username) . ", Please the password should be 8 digists at least one upercase letter, and one number</div>";
        }
    }

    // Once everything's filled out
    public function login() {

        // Just double check there are no errors first
        file_put_contents('../../temp/debugLogin.txt', 'begin' . "\n");
        if (!empty($this->error))
            return false;

        // Session expiration
        $minutes = parent::getOption('default_session');
        file_put_contents('../../temp/debugLogin.txt', json_encode($_SESSION) . "\n", FILE_APPEND);
        file_put_contents('../../temp/debugLogin.txt', 'minutes: ' . $minutes . "\n", FILE_APPEND);
        ini_set('session.cookie_lifetime', 60 * $minutes);

        session_regenerate_id();
        file_put_contents('../../temp/debugLogin.txt', json_encode($_SESSION) . "\n", FILE_APPEND);
        /* See if the admin requires new users to activate */
        if (parent::getOption('user-activation-enable')) :

            /** Check if user still requires activation. */
            $params = array(':user' => $this->user);
            $username = $this->username_type;
            $stmt = parent::query("SELECT * FROM `login_confirm` WHERE `{$username}` = :user AND `type` = 'new_user'", $params);

            $count = $stmt->rowCount();

            if ($count > 0)
                $_SESSION['jigowatt']['activate'] = 1;

        endif;

        // Save if user is restricted
        if (!empty($this->result['restricted']))
            $_SESSION['jigowatt']['restricted'] = 1;

        // Is the admin forcing a password update if encryption is not the desired method?
        if (parent::getOption('pw-encrypt-force-enable')) :

            $type = $this->getOption('pw-encryption');

            if (strlen($this->result['password']) == 32 && $type == 'SHA256')
                $_SESSION['jigowatt']['forcePwUpdate'] = 1;

            if (strlen($this->result['password']) != 32 && $type == 'MD5')
                $_SESSION['jigowatt']['forcePwUpdate'] = 1;

        endif;

        // Save user's current level
        $user_level = unserialize($this->result['user_level']);
        $_SESSION['jigowatt']['user_level'] = $user_level;

        $_SESSION['jigowatt']['email'] = $this->result['email'];

        $_SESSION['jigowatt']['gravatar'] = parent::get_gravatar($this->result['email'], true, 26);

        /** Check whether the user's level is disabled. */
        $params = array(':level' => $user_level[0]);
        $stmt = parent::query("SELECT `level_disabled`, `redirect` FROM `login_levels` WHERE `id` = :level;", $params);

        $disRow = $stmt->fetch();

        if (!empty($disRow['level_disabled']))
            $_SESSION['jigowatt']['level_disabled'] = 1;
        if (!empty($disRow['redirect']))
            $redirect = $disRow['redirect'];

        // Stay signed via checkbox?
        if (isset($_POST['remember'])) {
            ini_set('session.cookie_lifetime', 60 * 60 * 24 * 100); // Set to expire in 3 months & 10 days
            session_regenerate_id();
        }

        /** Store a timestamp. */
        if (parent::getOption('profile-timestamps-enable')) {

            $params = array(
                ':user_id' => $this->result['user_id'],
                ':ip' => $this->getIPAddress()
            );
            $stmt = parent::query("INSERT INTO `login_timestamps` (`user_id` ,`ip` ,`timestamp`) VALUES (:user_id, :ip, CURRENT_TIMESTAMP);", $params);
        }

        // And our magic happens here ! Let's sign them in
        $username = $this->username_type;
        $_SESSION['jigowatt']['username'] = $this->result[$username];

        // User ID of the logging in user
        $_SESSION['jigowatt']['user_id'] = $this->result['user_id'];

        if (empty($redirect))
            $redirect = $_SESSION['jigowatt']['referer'];

        unset(
                $_SESSION['jigowatt']['referer'],
                $_SESSION['jigowatt']['token'],
                $_SESSION['jigowatt']['facebookMisc'],
                $_SESSION['jigowatt']['twitterMisc'],
                $_SESSION['jigowatt']['openIDMisc']
        );
        //// email ///


        if ($this->result['user_level'] == 'a:1:{i:0;s:2:"10";}') {
            //if (!class_exists('dbname')) {
            require_once('/var/www/html/mrt/Server/email/class.phpmailer.php'); //echo 'b';
            //}
            if (!class_exists('zserver')) {
                require_once("/var/www/html/mrt/Server/zserver.php"); //echo 'c';
            }
            if (!class_exists('login_users')) {
                require_once("/var/www/html/mrt/Server/login_users.php"); //echo 'd';
            }
            if (!class_exists('dbname')) {
                //require_once("/var/www/html/mrt/Server/dbname.php"); //echo 'e';
            }

            $m = new dbname();
            $dbname = $m->getdbname(); //echo $dbname;
            date_default_timezone_set('America/New_York');
            $mail = new PHPMailer();
            $zserver = new zserver($dbname);
            $user = new login_users($dbname);
            $vava = 'info';
            $userr = $user->getAlllogin_usersForColumnValue('username', "'$vava'"); //echo var_dump($userr);
            $ar_id = $this->result['user_id'];
            $user2 = $user->getlogin_usersById($ar_id);
            if (count($userr) > 0) {
                foreach ($userr as $v) {
                    $userr = $v;
                }
                $zcal_user = $userr->getemail();
                $zcal_pass = base64_decode($userr->getpassz());

                $to = $zcal_user;
                $body = 'The user ' . $user2->getusername() . ' has been login in the system';
                echo $body;
                $path = '';
                $from = $zcal_user;
                $cc = '';
                $bcc = '';
                $subject = 'Report ';
                $mail->IsSMTP();
                $server = $zserver->getzserverById(1);
                $host = $server->getzurl(); //
                if (strpos($str, 'https://') != false) {
                    $host = substr($str, 8);
                }
                if (strpos($str, 'http://') != false) {
                    $host = substr($str, 7);
                }$host = str_replace('http://', '', $host);
                $host = str_replace('https://', '', $host);
                $mail->Host = $host;
                $mail->SMTPDebug = 1;
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = "tls";
                $mail->Host = $host;
                $mail->Port = 587;
                $mail->Username = $zcal_user;
                $mail->Password = $zcal_pass;
                $mail->SetFrom($from, '');

                $mail->Subject = $subject;
                $mail->MsgHTML($body);
                $address = $to;
                $mail->AddAddress($address, "");

                if (!$mail->Send()) {
                    echo "Mailer Error: " . $mail->ErrorInfo;
                } else {
                    echo "Message sent";
                }
            }
        }


        ////email///
        // Redirect after it's all said and done
        header("Location: " . $redirect);
        exit();
    }

}

$login = new Login();
