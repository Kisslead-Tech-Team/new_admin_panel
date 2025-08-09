<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    protected $session = null;
	protected $db;
    protected $userData;

public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
{
    parent::initController($request, $response, $logger);

    helper('sessionCheck'); // ✅ load custom helper

    $this->session = \Config\Services::session();
    $this->db = db_connect();

    // Skip session check on login or root page
    $uri = service('uri')->getSegment(1); // Get first segment

    $publicRoutes = ['login', '', 'auth']; // Publicly accessible routes

    if (!sessionCheck() && !in_array($uri, $publicRoutes)) {
        redirect()->to('/')->send(); // 🔁 prevent loop
        exit;
    }
}

    public function response_message($messData){


        if(!$messData || !$messData['code']){
            return json_encode([
                "code" => 400,
                "msg" => "Something went wrong!"
            ]);
        }

        return json_encode($messData);
    }

    public function encryptData($data){
        return base64_encode(json_encode($data));
    }

    public function decryptData($data){
        return json_decode(base64_decode($data));
    }

    public $validationRule = [
        'userfile' => [
            'label' => 'Image File',
            'rules' => [
                'uploaded[userfile]',
                'is_image[userfile]',
                'mime_in[userfile,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
                'max_size[userfile,100]',
                'max_dims[userfile,1024,768]',
            ],
        ],
    ];
}
