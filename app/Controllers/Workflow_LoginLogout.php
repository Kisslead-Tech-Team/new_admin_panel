<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\LoginModel;

class Workflow_LoginLogout extends ResourceController
{
    private $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function logincheck(){
        $this->session = \Config\Services::session();

        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $loginModel = new LoginModel;

        $userData = $loginModel->where('username', $username)->first();


        $resultData = [];

        if ($userData) {
            $pwd = $userData['password'];
            if ($pwd === $password) {
                $ses_data = [
                    'username' => $userData['username'],
                    'password' => $userData['password'],
                    'usertype' => $userData['user_type']
                ];

                $this->session->set($ses_data);

                $resultData['message'] = 'success';
                $resultData['code'] = '200';
            } else {
                $resultData['message'] = 'Incorrect username or password';
                $resultData['code'] = '400';
            }
        }else{
            $resultData['code'] = '400';
        }


        return $this->response->setJSON($resultData);
    }

    public function logout()
    {        
        $this->session = \Config\Services::session();
        $this->session->destroy();
        return redirect()->to('/');
    }
}
