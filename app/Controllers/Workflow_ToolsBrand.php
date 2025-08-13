<?php

namespace App\Controllers;
use CodeIgniter\Files\File;

use App\Models\ToolsBrandModel;

class Workflow_ToolsBrand extends BaseController
{

    public function __construct()
    {
        
    }






public function getBrand()
{
    $draw   = (int) ($this->request->getGet('draw') ?? 1);
    $start  = (int) ($this->request->getGet('start') ?? 0);
    $length = $this->request->getGet('length'); // don't cast yet, so we can check if null
    $search = $this->request->getGet('search');

    $brandModel = new ToolsBrandModel();
    $brandModel->where('flag', 1);

    if (!empty($search)) {
        $brandModel->groupStart()
            ->like('brand_name', $search)
            ->orLike('url', $search)
            ->groupEnd();
    }

    // Get total filtered records count
    $totalRecords = $brandModel->countAllResults(false);

    // Add order by created_at descending
    $brandModel->orderBy('created_at', 'DESC');

    // If length is defined and not -1, apply limit, else fetch all
    if (!empty($length) && (int)$length !== -1) {
        $rows = $brandModel->findAll((int)$length, $start);
    } else {
        $rows = $brandModel->findAll(); // get all brands
    }

    $responseData = [
        'draw' => $draw,
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $totalRecords,
        'data' => $rows
    ];

    echo $this->response_message([
        'code' => 200,
        'data' => $responseData
    ]);
}

public function getBrandOption()
{
    $brandModel = new ToolsBrandModel();

    // Only active brands
    $brandModel->where('flag', 1);

    // Only select brand_id and brand_name
    $rows = $brandModel
        ->select('brand_id, brand_name')
        ->orderBy('brand_name', 'ASC')
        ->findAll();

    echo $this->response_message([
        'code' => 200,
        'data' => $rows
    ]);
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


    // Check if another brand already has this name (excluding current brand)
    $brand_name_check = $brandModel
        ->where('brand_id !=', $data['brand_id'])
        ->where('url', $data['url'])
        ->where('flag', 1)
        ->first();

    if (!$brand_name_check) {
        // Check if brand exists
        $brand_check = $brandModel->where('brand_id', $data['brand_id'])->first();
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