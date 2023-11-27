<?php

namespace CSY2038\Controllers;
use CSY\MyPDO;
class Validations
{
    public function validateRegisterForm($fullname, $username, $password, $usertype)
    {
        $errors = [];

        if (empty($fullname)) {
            $errors[] = 'Full name is required';
        }
        if (empty($username)) {
            $errors[] = 'Username is required';
        }
        if (empty($password)) {
            $errors[] = 'Password is required';
        }
        if (empty($usertype)) {
            $errors[] = 'User type is required';
        }

        return $errors;
    }
    public function validateloginForm($username, $password)
    {
        $errors = [];

        if (empty($username)) {
            $errors[] = 'Username is required';
        }
        if (empty($password)) {
            $errors[] = 'Password is required';
        }

        return $errors;
    }
}