<?php

namespace App\Controller;

use App\Model\DBCommunication as DBCommunication;
use Exception as Exception;


class EmailException extends Exception
{
}

class AgreementException extends Exception
{
}

class UserFormValidation
{
    private static string $email = '';
    private static string $agreement = '';
    private const EMAILREGEX = "/[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/";
    private static string $emailError = '';
    private static string $agreementError = '';

    private static function getInputs()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            UserFormValidation::$email = UserFormValidation::test_input($_POST['email']);
            UserFormValidation::$agreement = UserFormValidation::test_input($_POST['agreement']);
        }
    }

    /**
     * @throws EmailException
     */
    private static function validateEmail()
    {
        if (UserFormValidation::$email === '') {
            throw new EmailException('Email address is required');
        } elseif (preg_match(self::EMAILREGEX, UserFormValidation::$email)) {
            if (preg_match('/(\.co$)/', UserFormValidation::$email)) {
                throw new EmailException('We are not accepting subscriptions from Colombia emails');
            }
        } else {
            throw new EmailException('Please provide a valid e-mail address');
        }
    }

    /**
     * @throws AgreementException
     */
    private static function validateAgreement()
    {
        if (UserFormValidation::$agreement !== 'on') {
            throw new AgreementException('You must accept the terms and conditions');
        }
    }

    private static function test_input($data): string
    {
        $data = trim($data);
        $data = stripslashes($data);
        return htmlspecialchars($data);
    }

    public static function validate()
    {
        UserFormValidation::getInputs();

        try {
            UserFormValidation::validateEmail();
        } catch (Exception $e) {
            UserFormValidation::$emailError = $e->getMessage();
        }

        try {
            UserFormValidation::validateAgreement();
        } catch (Exception $e) {
            UserFormValidation::$agreementError = $e->getMessage();
        }

        if (UserFormValidation::$emailError === '' && UserFormValidation::$agreementError === '') {
            DBCommunication::pushEmail($_POST['email']);
            $_GET = array();
            $_POST = array();

            header("Location: \subscribed.php");
            exit();
        }
    }

    public static function getEmailError(): string
    {
        return UserFormValidation::$emailError;
    }

    public static function getAgreementError(): string
    {
        return UserFormValidation::$agreementError;
    }
}
