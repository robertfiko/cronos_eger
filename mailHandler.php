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

        echo '<p>Kérlem figyeljen oda, a "Nem vagyok rorot" jelölőnégyzet kitöltésekor.</p>';
    } else if ($captcha_success->success==true) {

        $to = "trener.nagy.agnes@gmail.com";
        $subject = "EgerTárs - ".$_POST['name'];

        $message = "
            <html lang='hu'>
                <head>
                    <title>EgerTárs Kapcsolat felévtel</title>
                </head>
                <body>
                    <p>Kedves Irodavezető!</p>
                    <p>Kapcsolatfelvétel történt az alábbi adatokkal: </p>
                    <table>
                        <tr>
                            <td>Név: </td>
                            <td>".$_POST['name']."</td>
                        </tr>
                        <tr>
                            <td>E-mail cím: </td>
                            <td>".$_POST['email']."</td>
                        </tr>
                        <tr>
                            <td>Telefon: </td>
                            <td>".$_POST['phone']."</td>
                        </tr>
                    </table>
                    <p>Üzenet: </p>
                    <pre>".$_POST['msg']."</pre>
                    <h3>Az üzenetre való válaszolással az ÜGYFÉLNEK válaszolhatsz!</h3>
                </body>
            </html>
";

// Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
        $headers .= 'From: CTK Eger Kapcsolat <mailserver@fiko.hu> '. " \r\n";
        $headers .= 'Reply-To: '.$_POST['name'].'<'.$_POST['email'].'>' . "\r\n";
        $headers .= 'Sender: mailserver@fiko.hu' . "\r\n";

        mail($to, $subject, $message, $headers);
        echo '<p>Levél küldés sikeres!</p>';
        echo '<meta http-equiv="refresh" content="2; url=http://egertarskereso.hu">';



    }

}