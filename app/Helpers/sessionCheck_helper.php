<?php

function sessionCheck(){

    $session = \Config\Services::session();
    $userData = $session->get('username');

    if($userData){  
        return true;
    }

    return false;
}