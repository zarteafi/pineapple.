<?php


$go = false;
$emailMsg = '';
$agreementMsg = '';

function validate(&$emailMsg, &$agreementMsg)
{
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailMsg = 'Please provide a valid e-mail address';
        } elseif (preg_match('/(\.co$)/', $email)) {
            $emailMsg = 'We are not accepting subscriptions from Colombia emails';
        } else {
            $emailMsg = '';
        }
    } else {
        $emailMsg = 'Email address is required';
    }

    if (isset($_POST['agreement'])) {
        if ($_POST['agreement'] !== 'on') {
            $agreementMsg = 'You must accept the terms and conditions';
        } else {
            $agreementMsg = '';
        }
    } else {
        $agreementMsg = 'You must accept the terms and conditions';
    }

    if ($emailMsg === '' && $agreementMsg === '') {
        require($_SERVER['DOCUMENT_ROOT'] . '/db/db.php');
        pushEmail($_POST['email']);
        $_GET = array();
        $_POST = array();

        header("Location: \subscribed.php");
        exit();
    }
}