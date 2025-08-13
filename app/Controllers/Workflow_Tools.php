<?php

namespace App\Controllers;
use CodeIgniter\Files\File;

use App\Models\ToolsModel;
use App\Models\ToolsImageModel;
helper('image');



class Workflow_Tools extends BaseController
{

    public function __construct()
    {
        
    }

//this function only for tool_img

public function getTools()
{
    $draw   = (int) ($this->request->getGet('draw') ?? 1);
    $start  = (int) ($this->request->getGet('start') ?? 0);
    $length = $this->request->getGet('length');
    $search = $this->request->getGet('search');

    $toolsModel = new ToolsModel();

    // Base query with joins
    $toolsModel
        ->select('tools_tbl.*, tools_brand_tbl.brand_name, tools_category_tbl.category_name')
        ->join('tools_brand_tbl', 'tools_brand_tbl.brand_id = tools_tbl.brand_id', 'left')
        ->join('tools_category_tbl', 'tools_category_tbl.category_id = tools_tbl.category_id', 'left')
        ->where('tools_tbl.flag', 1);

    if (!empty($search)) {
        $toolsModel->groupStart()
            ->like('tools_tbl.tools_name', $search)
            ->orLike('tools_tbl.tools_url', $search)
            ->orLike('tools_brand_tbl.brand_name', $search)
            ->orLike('tools_category_tbl.category_name', $search)
            ->groupEnd();
    }

    // Get total records
    $totalRecords = $toolsModel->countAllResults(false);

    // Ordering
    $toolsModel->orderBy('tools_tbl.created_at', 'DESC');

    // Apply pagination
    if (!empty($length) && (int)$length !== -1) {
        $rows = $toolsModel->findAll((int)$length, $start);
    } else {
        $rows = $toolsModel->findAll();
    }

    // Get images for each tool
    $toolsImgModel = new ToolsImageModel();
    foreach ($rows as &$tool) {
        $images = $toolsImgModel
            ->select('tools_img_id, image_path')
            ->where('tools_id', $tool['tools_id'])
            ->findAll();

        // Store as array of associative arrays
        $tool['images'] = $images;
    }
        $responseData = [
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $rows
        ];

        return $this->response_message([
            'code' => 200,
            'data' => $responseData
        ]);
}






public function insertTools()
{
    $toolsModel = new ToolsModel;
    $request    = \Config\Services::request();
    $data       = $request->getPost();

    $brandId    = $data['brand_id'];
    $categoryId = $data['category_id'];
    $toolsUrl   = $data['tools_url'];

    // ---------- Check if Tool already exists ----------
    $toolsCheck = $toolsModel->where([
        'tools_url' => $toolsUrl,
        'brand_id'  => $brandId,
        'category_id' => $categoryId,
        'flag' => 1
    ])->first();

    if ($toolsCheck) {
   
         echo $this->response_message([
            'code' => 400,
            'msg'  => "Tool with this URL already exists for the selected brand/category."
        ]);
        return;
    }

    // ---------- Build folder paths ----------
    $pdfPath = "uploads/tools_pdf/brand_{$brandId}/category_{$categoryId}/{$toolsUrl}/";
    $imgPath = "uploads/tools_img/brand_{$brandId}/category_{$categoryId}/{$toolsUrl}/";

    // Ensure directories exist
    if (!is_dir($pdfPath)) mkdir($pdfPath, 0777, true);
    if (!is_dir($imgPath)) mkdir($imgPath, 0777, true);

    // ---------- Upload PDF ----------
    $pdfFile = $this->request->getFile('tools_brochure');
    $pdfFileName = null;

    if ($pdfFile && $pdfFile->isValid()) {
        if (strtolower($pdfFile->getExtension()) === 'pdf') {
            $pdfFileName = $pdfFile->getRandomName();
            $pdfFile->move($pdfPath, $pdfFileName);
            
        } else {

            echo $this->response_message([
            'code' => 400,
            'msg'  => "Only PDF files are allowed for brochure."
        ]);
        return;
       
        }
    }

    // ---------- Upload Images ----------
   $imageFiles = $this->request->getFiles();
    $uploadedImages = processUploadedImages($imageFiles, 'tools_img', $imgPath, 60, true);


    
    $tools_brochure_path = $pdfFileName ? $pdfPath . $pdfFileName : null;

    // ---------- Insert into tools_tbl ----------
    $toolsId = $toolsModel->insert([
        'brand_id'        => $brandId,
        'category_id'     => $categoryId,
        'tools_name'      => $data['tools_name'],
        'tools_url'       => $toolsUrl,
        'tools_description' => $data['tools_description'],
        'tools_brochure'    => $tools_brochure_path,
        'flag'            => 1
    ]);

    // ---------- Insert into tools_img_tbl ----------
    if (!empty($uploadedImages)) {
        $toolsImageModel = new ToolsImageModel;
        $imageData = [];

        foreach ($uploadedImages as $imgName) {
            $imageData[] = [
                'tools_id'   => $toolsId,
                'image_path' => $imgName
            ];
        }
        $toolsImageModel->insertBatch($imageData);
    }


      echo $this->response_message([
            'code' => 200,
            'msg'  => "Tools data inserted successfully."
        ]);
        return;


  
}


    

public function updateTools()
{
    $toolsModel = new ToolsModel();
    $toolsImageModel = new ToolsImageModel();
    $request = \Config\Services::request();
    $data = $request->getPost();

    $toolsId = $data['tools_id'];
    $brandId = $data['brand_id'];
    $categoryId = $data['category_id'];
    $toolsUrl = $data['tools_url'];

    // Check if tools_url already exists for another tool (excluding this tool)
    $toolsCheck = $toolsModel->where([
        'tools_url' => $toolsUrl,
        'brand_id' => $brandId,
        'category_id' => $categoryId,
        'flag' => 1
    ])->where('tools_id !=', $toolsId)->first();

    if ($toolsCheck) {
        echo $this->response_message([
            'code' => 400,
            'msg'  => "Tool with this URL already exists for the selected brand/category."
        ]);
        return;
    }

    // Build folder paths
    $pdfPath = "uploads/tools_pdf/brand_{$brandId}/category_{$categoryId}/{$toolsUrl}/";
    $imgPath = "uploads/tools_img/brand_{$brandId}/category_{$categoryId}/{$toolsUrl}/";

    if (!is_dir($pdfPath)) mkdir($pdfPath, 0777, true);
    if (!is_dir($imgPath)) mkdir($imgPath, 0777, true);

    // Handle PDF upload and replace old PDF
    $pdfFile = $this->request->getFile('tools_brochure');
    $pdfFileName = null;
    $currentPdfPath = null;

    // Get current PDF path from DB to delete if replaced
    $currentTool = $toolsModel->find($toolsId);
    if ($currentTool) {
        $currentPdfPath = $currentTool['tools_brochure'];
    }

    if ($pdfFile && $pdfFile->isValid()) {
        if (strtolower($pdfFile->getExtension()) === 'pdf') {
            // Delete old PDF file if exists
            if ($currentPdfPath && file_exists($currentPdfPath)) {
                @unlink($currentPdfPath);
            }

            $pdfFileName = $pdfFile->getRandomName();
            $pdfFile->move($pdfPath, $pdfFileName);
            $pdfFileName = $pdfPath . $pdfFileName;
        } else {
            echo $this->response_message([
                'code' => 400,
                'msg'  => "Only PDF files are allowed for brochure."
            ]);
            return;
        }
    } else {
        // Keep current PDF if no new upload
        $pdfFileName = $currentPdfPath;
    }

    // Handle multiple image uploads (add new images)
    $imageFiles = $this->request->getFiles();
    $uploadedImages = processUploadedImages($imageFiles, 'tools_img', $imgPath, 60, true);



    // Update tools_tbl record
    $updateData = [
        'brand_id' => $brandId,
        'category_id' => $categoryId,
        'tools_name' => $data['tools_name'],
        'tools_url' => $toolsUrl,
        'tools_description' => $data['tools_description'],
        'tools_brochure' => $pdfFileName,
        'flag' => 1
    ];

    $updated = $toolsModel->update($toolsId, $updateData);

    // Insert new images into tools_img_tbl if any
    if (!empty($uploadedImages)) {
        $imageData = [];
        foreach ($uploadedImages as $imgPathFull) {
            $imageData[] = [
                'tools_id' => $toolsId,
                'image_path' => $imgPathFull
            ];
        }
        $toolsImageModel->insertBatch($imageData);
    }

    if ($updated) {
        echo $this->response_message([
            'code' => 200,
            'msg' => "Tools updated successfully."
        ]);
    } else {
        echo $this->response_message([
            'code' => 400,
            'msg' => "Failed to update tools."
        ]);
    }
}





public function deleteToolsImage()
{
    $request = \Config\Services::request();
    $toolsImgModel = new ToolsImageModel();

    $tools_id     = $request->getPost('tools_id');
    $tools_img_id = $request->getPost('tools_img_id');
    $image_path   = $request->getPost('image_path');

    if (empty($tools_id) || empty($tools_img_id) || empty($image_path)) {
        return $this->response_message([
            'code' => 400,
            'msg'  => 'Invalid request data'
        ]);
    }

    // 1️⃣ Delete file from directory
    $fullPath = FCPATH . $image_path;
    if (file_exists($fullPath)) {
        @unlink($fullPath);
    }

    // 2️⃣ Delete record from DB
    $delete = $toolsImgModel
        ->where('tools_img_id', $tools_img_id)
        ->where('tools_id', $tools_id)
        ->delete();

    if ($delete) {
        // 3️⃣ Get remaining images
    $remainingImages = $toolsImgModel
    ->select('tools_img_id, image_path')
    ->where('tools_id', $tools_id)
    ->findAll();

        return $this->response_message([
            'code' => 200,
            'msg'  => 'Image deleted successfully!',
            'data' => $remainingImages
        ]);
    }

    return $this->response_message([
        'code' => 500,
        'msg'  => 'Failed to delete image'
    ]);
}


public function deleteTools()
{
    $toolsModel = new ToolsModel();
    $toolsImgModel = new ToolsImageModel();
    $request = \Config\Services::request();

    $toolsId = $request->getPost('tools_id');
    $brandId = $request->getPost('brand_id');
    $categoryId = $request->getPost('category_id');
    $toolsUrl = $request->getPost('tools_url');

    if (!$toolsId || !$brandId || !$categoryId || !$toolsUrl) {
        return $this->response->setJSON([
            'code' => 400,
            'msg'  => 'Missing required parameters.'
        ]);
    }

    // Build folder paths
    $pdfPath =  "uploads/tools_pdf/brand_{$brandId}/category_{$categoryId}/{$toolsUrl}/";
    $imgPath =  "uploads/tools_img/brand_{$brandId}/category_{$categoryId}/{$toolsUrl}/";

    // Helper function to recursively delete folder and files
    function deleteFolder($folderPath) {
        if (!is_dir($folderPath)) {
            return;
        }

        // Get all files including hidden ones
        $files = array_diff(scandir($folderPath), ['.', '..']);

        foreach ($files as $file) {
            $fullPath = rtrim($folderPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file;

            if (is_dir($fullPath)) {
                deleteFolder($fullPath);
            } else {
                if (!@unlink($fullPath)) {
                    error_log("Failed to delete file: $fullPath");
                }
            }
        }

        // Try to delete folder itself
        if (!@rmdir($folderPath)) {
            error_log("Failed to delete folder: $folderPath");
        }
    }

    // Delete PDF folder
    deleteFolder($pdfPath);

    // Delete Images folder
    deleteFolder($imgPath);

    // Delete images records for this tool
    $toolsImgModel->where('tools_id', $toolsId)->delete();

    // Delete tools record
    $deleted = $toolsModel->delete($toolsId);

    if ($deleted) {
        return $this->response->setJSON([
            'code' => 200,
            'msg'  => 'Tool and associated files deleted successfully.'
        ]);
    } else {
        return $this->response->setJSON([
            'code' => 500,
            'msg'  => 'Failed to delete tool.'
        ]);
    }
}

}