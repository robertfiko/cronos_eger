<?php
require_once "secure/key.php";
if (isset($_POST['sendbtn'])) {

    //
    //      RECAPTCHA VERIFICATION
    //

    $sender_name    = stripslashes($_POST["sender_name"]);
    $sender_email   = stripslashes($_POST["sender_email"]);
    $sender_message = stripslashes($_POST["sender_message"]);
    $response       = $_POST["g-recaptcha-response"];

    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret'    => $secret_recaptcha_key,
        'response'  => $_POST["g-recaptcha-response"]
    );

    $options = array(
        'http' => array (
            'method'    => 'POST',
            'content'   => http_build_query($data)
        )
    );
    $context         = stream_context_create($options);
    $verify          = file_get_contents($url, false, $context);
    $captcha_success = json_decode($verify);

    if ($captcha_success->success==false) {

        echo "<p>You are a bot! Go away!</p>";
    } else if ($captcha_success->success==true) {
        echo "<p>You are not not a bot!</p>";
    }

}