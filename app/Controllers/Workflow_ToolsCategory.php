<?php

namespace App\Controllers;
use CodeIgniter\Files\File;

use App\Models\ToolsCategoryModel;

class Workflow_ToolsCategory extends BaseController
{

    public function __construct()
    {
        
    }



public function getCategory()
{
    $draw   = (int) ($this->request->getGet('draw') ?? 1);
    $start  = (int) ($this->request->getGet('start') ?? 0);
    $length = $this->request->getGet('length');
    $search = $this->request->getGet('search');

    $categoryModel = new ToolsCategoryModel();
    $categoryModel->where('flag', 1);

    if (!empty($search)) {
        $categoryModel->groupStart()
            ->like('category_name', $search)
            ->orLike('category_url', $search)
            ->groupEnd();
    }

    // Get total filtered records count
    $totalRecords = $categoryModel->countAllResults(false);

    // Order by creation date
    $categoryModel->orderBy('created_at', 'DESC');

    // If length is defined and not -1, apply limit, else fetch all
    if (!empty($length) && (int)$length !== -1) {
        $rows = $categoryModel->findAll((int)$length, $start);
    } else {
        $rows = $categoryModel->findAll();
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


public function getCategoryOption()
{
    $categoryModel = new ToolsCategoryModel();

    // Only active brands
    $categoryModel->where('flag', 1);

    // Only select brand_id and brand_name
    $rows = $categoryModel
        ->select('category_id, category_name')
        ->orderBy('category_name', 'ASC')
        ->findAll();

    echo $this->response_message([
        'code' => 200,
        'data' => $rows
    ]);
}



public function insertCategory()
{


 
    $categoryModel = new ToolsCategoryModel();
    $request = \Config\Services::request();
    $data = $request->getPost(); // should contain brand_name and brand_id


    // Check if brand_name already exists (and flag is 1)
    $categoryCheck = $categoryModel->where([
        'category_url' => $data['category_url'],
        'flag'       => 1
    ])->first();

    if (!$categoryCheck) {
        if ($data && $categoryModel->insert($data, false)) {
            echo $this->response_message([
                'code' => 200,
                'msg'  => "Category inserted successfully!"
            ]);
            return;
        }

        // Insert failed
        echo $this->response_message(false);
    } else {
        // Brand already exists
        echo $this->response_message([
            'code' => 400,
            'msg'  => "Category name already exists! Try another name."
        ]);
        return;
    }
}
    

public function updateCategory()
{
    $categoryModel = new ToolsCategoryModel();
    $request = \Config\Services::request();
    $data = $request->getPost();

    // Check if another brand already has this name (excluding current brand)
    $category_name_check = $categoryModel
        ->where('category_id !=', $data['category_id'])
        ->where('category_url', $data['category_url'])
        ->where('flag', 1)
        ->first();

    if (!$category_name_check) {
        // Check if brand exists
        $brand_check = $categoryModel->where('category_id', $data['category_id'])->first();
        if ($brand_check) {
            $update = $categoryModel->save($data);
            if ($data && $update) {
                if ($categoryModel->db->affectedRows()) {
                    echo $this->response_message([
                        'code' => 200,
                        'msg'  => "Category updated successfully!"
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
            'msg'  => "Category name is already there, try another name!"
        ]);
        return;
    }
}


public function deleteCategory()
{
    $categoryModel = new ToolsCategoryModel;
    $request = \Config\Services::request();

    $data = [
        'category_id' => $request->getPost('category_id'),
        'flag'     => 0
    ];

    $delete = $categoryModel->save($data);

    if ($delete) {
        if ($categoryModel->db->affectedRows()) {
            echo $this->response_message([
                'code' => 200,
                'msg'  => "Category deleted successfully!"
            ]);
            return;
        }
    }

    echo $this->response_message(false);
}

}