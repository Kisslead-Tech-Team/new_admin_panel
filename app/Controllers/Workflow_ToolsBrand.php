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
    helper('image');
    $brandModel = new ToolsBrandModel();
    $request = \Config\Services::request();
    $data = $request->getPost(); // should contain brand_name, url, etc.

    // Check if brand_name already exists (and flag is 1)
    $brandCheck = $brandModel->where([
        'url'  => $data['url'],
        'flag' => 1
    ])->first();

    if ($brandCheck) {
        echo $this->response_message([
            'code' => 400,
            'msg'  => "Brand name already exists! Try another name."
        ]);
        return;
    }

    // Ensure logo folder exists
    $logoPath = "uploads/brand_logo/";
    if (!is_dir($logoPath)) {
        mkdir($logoPath, 0777, true);
    }

    // Process uploaded logo image
    $imageFiles = $this->request->getFiles();
    $uploadedLogos = processUploadedImages(
        $imageFiles,
        'logo_path', // adjust this if your input name is different
        $logoPath,
        80,    // quality
        true   // convert to webp
    );

    if (empty($uploadedLogos)) {
        echo $this->response_message([
            'code' => 400,
            'msg'  => "No logo uploaded or image processing failed!"
        ]);
        return;
    }

    $logoImgPath = $uploadedLogos[0];
    $insertData = [
        'brand_name' => $data['brand_name'],
         'url' => $data['url'],
        'logo_path'  => $logoImgPath
        ];

    if ($brandModel->insert($insertData, false)) {
        echo $this->response_message([
            'code' => 200,
            'msg'  => "Gallery inserted successfully!"
        ]);
    } else {
        echo $this->response_message(false);
    }
}
    

public function updateBrand()
{
    helper('image');
    $brandModel = new ToolsBrandModel();
    $request = \Config\Services::request();
    $data = $request->getPost();

    $brandId = $data['brand_id'];

    // Check if another brand already has this URL (excluding current brand)
    $brandNameCheck = $brandModel
        ->where('brand_id !=', $brandId)
        ->where('url', $data['url'])
        ->where('flag', 1)
        ->first();

    if ($brandNameCheck) {
        echo $this->response_message([
            'code' => 400,
            'msg'  => "Brand name is already there, try another name!"
        ]);
        return;
    }

    // Fetch current brand record
    $existingBrand = $brandModel->find($brandId);
    if (!$existingBrand) {
        echo $this->response_message([
            'code' => 400,
            'msg'  => "Brand not found!"
        ]);
        return;
    }

    // Ensure logo folder exists
    $logoPath = "uploads/brand_logo/";
    if (!is_dir($logoPath)) {
        mkdir($logoPath, 0777, true);
    }

    // Process uploaded logo image
    $imageFiles = $this->request->getFiles();
    $uploadedLogos = processUploadedImages(
        $imageFiles,
        'logo_path', // Form input name
        $logoPath,
        80,    // quality
        true   // convert-to-webp
    );

    // If new logo uploaded → delete old logo
    if (!empty($uploadedLogos)) {
        $newLogoPath = $uploadedLogos[0];
        if (!empty($existingBrand['logo_path']) && file_exists($existingBrand['logo_path'])) {
            unlink($existingBrand['logo_path']);
        }
    } else {
        // No new logo uploaded → keep old logo path
        $newLogoPath = $existingBrand['logo_path'];
    }

    // Prepare update data
    $updateData = [
        'brand_id'  => $brandId,
        'brand_name'=> $data['brand_name'], // Add any other brand fields you update
        'url'       => $data['url'],
        'logo_path' => $newLogoPath,
        // add other updatable fields as needed
    ];

    $update = $brandModel->save($updateData);

    if ($update) {
        if ($brandModel->db->affectedRows()) {
            echo $this->response_message([
                'code' => 200,
                'msg'  => "Brand updated successfully!"
            ]);
        } else {
            echo $this->response_message([
                'code' => 400,
                'msg'  => "No changes"
            ]);
        }
    } else {
        echo $this->response_message(false);
    }
}



public function deleteBrand()
{
    $brandModel = new ToolsBrandModel();
    $request = \Config\Services::request();

    $brandId = $request->getPost('brand_id');

    // Find current brand so we can delete the logo image
    $brand = $brandModel->find($brandId);
    if (!$brand) {
        echo $this->response_message([
            'code' => 400,
            'msg'  => "Brand not found!"
        ]);
        return;
    }

    // Delete logo image from server if it exists
    if (!empty($brand['logo_path']) && file_exists($brand['logo_path'])) {
        unlink($brand['logo_path']);
    }

    // Only set flag = 0, do NOT delete db record
    $data = [
        'brand_id' => $brandId,
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