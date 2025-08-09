<?php

namespace App\Controllers;

use App\Models\UserModel;

class Workflow_User extends BaseController
{
    public function getUserCount(){
        $UserModel = new UserModel;
        $data = $UserModel->where('flag', 1)->findAll();

        echo $this->response_message([
            'code' => 200,
            'data' => count($data)
        ]); return;
    }
}