<?php

namespace App\Controllers;
use CodeIgniter\Files\File;

use App\Models\ToolsBrandModel;

class Workflow_ToolsBrand extends BaseController
{

    public function __construct()
    {
        
    }

    public function getBrand(){

        $brandModel = new ToolsBrandModel;
        $data = $brandModel->where('flag', 1)->findAll();

        if($data){
            echo $this->response_message([
                'code' => 200,
                'data' => json_encode($data)
            ]); return;
        }

        echo $this->response_message(false);
    }

    public function getSpecificBrand(){

        $brandModel = new ToolsBrandModel;
        $request = \Config\Services::request();
        $data =  $request->getPost();

        $response = $brandModel->where('brand_id', $data['brand_id'])->first();
        
        if($response){
            echo $this->response_message([
                'code' => 200,
                'data' => json_encode($data)
            ]);
        }

        echo $this->response_message(false);
    }



public function insertBrand()
{
    $brandModel = new ToolsBrandModel();
    $request = \Config\Services::request();
    $data = $request->getPost(); // should contain brand_name and brand_id

    // Check if brand_name already exists (and flag is 1)
    $brandCheck = $brandModel->where([
        'url' => $data['url'],
        'flag'       => 1
    ])->first();

    if (!$brandCheck) {
        if ($data && $brandModel->insert($data, false)) {
            echo $this->response_message([
                'code' => 200,
                'msg'  => "Brand inserted successfully!"
            ]);
            return;
        }

        // Insert failed
        echo $this->response_message(false);
    } else {
        // Brand already exists
        echo $this->response_message([
            'code' => 400,
            'msg'  => "Brand name already exists! Try another name."
        ]);
        return;
    }
}
    

public function updateBrand()
{
    $brandModel = new ToolsBrandModel();
    $request = \Config\Services::request();
    $data = $request->getPost();
    $brand_id = $data['brand_id'];

    // Check if another brand already has this name (excluding current brand)
    $brand_name_check = $brandModel
        ->where('brand_id !=', $brand_id)
        ->where('url', $data['url'])
        ->where('flag', 1)
        ->first();

    if (!$brand_name_check) {
        // Check if brand exists
        $brand_check = $brandModel->where('brand_id', $brand_id)->first();
        if ($brand_check) {
            $update = $brandModel->save($data);
            if ($data && $update) {
                if ($brandModel->db->affectedRows()) {
                    echo $this->response_message([
                        'code' => 200,
                        'msg'  => "Brand updated successfully!"
                    ]);
                    return;
                } else {
                    echo $this->response_message([
                        'code' => 400,
                        'msg'  => "No changes"
                    ]);
                    return;
                }
            }
        }
        echo $this->response_message(false);
    } else {
        echo $this->response_message([
            'code' => 400,
            'msg'  => "Brand name is already there, try another name!"
        ]);
        return;
    }
}


    public function deleteBrand()
{
    $brandModel = new ToolsBrandModel;
    $request = \Config\Services::request();

    $data = [
        'brand_id' => $request->getPost('brand_id'),
        'flag'     => 0
    ];

    $delete = $brandModel->save($data);

    if ($delete) {
        if ($brandModel->db->affectedRows()) {
            echo $this->response_message([
                'code' => 200,
                'msg'  => "Brand deleted successfully!"
            ]);
            return;
        }
    }

    echo $this->response_message(false);
}

}