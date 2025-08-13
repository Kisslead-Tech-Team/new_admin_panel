<?php

namespace App\Controllers;
use CodeIgniter\Files\File;

use App\Models\GalleryModel;

class Workflow_Gallery extends BaseController
{

public function __construct()
{
    
}



public function getGallery()
{
    $draw   = (int) ($this->request->getGet('draw') ?? 1);
    $start  = (int) ($this->request->getGet('start') ?? 0);
    $length = $this->request->getGet('length'); // don't cast yet, so we can check if null
    $search = $this->request->getGet('search');

    $galleryModel = new GalleryModel();
    $galleryModel->where('flag', 1);

    if (!empty($search)) {
        $galleryModel->groupStart()
            ->like('gallery_name', $search)
            ->groupEnd();
    }

    // Get total filtered records count
    $totalRecords = $galleryModel->countAllResults(false);

    // Add order by created_at descending
    $galleryModel->orderBy('created_at', 'DESC');

    // If length is defined and not -1, apply limit, else fetch all
    if (!empty($length) && (int)$length !== -1) {
        $rows = $galleryModel->findAll((int)$length, $start);
    } else {
        $rows = $galleryModel->findAll(); // get all brands
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



public function insertGallery()
{

  
    helper('image');
    $galleryModel = new GalleryModel();
    $request = \Config\Services::request();
    $data = $request->getPost();

    // Check if gallery_name already exists (and flag = 1)
    $nameCheck = $galleryModel->where([
        'gallery_name' => $data['gallery_name']
    ])->first();

    if ($nameCheck) {
        echo $this->response_message([
            'code' => 400,
            'msg'  => "Gallery name already exists!"
        ]);
        return;
    }

    // Ensure folder exists
    $imgPath = "uploads/gallery_img/";
    if (!is_dir($imgPath)) {
        mkdir($imgPath, 0777, true);
    }

    // Process uploaded gallery image
    $imageFiles = $this->request->getFiles();
    $uploadedImages = processUploadedImages(
        $imageFiles,
        'gallery_img', // input name in your form
        $imgPath,
        80, // quality
        true // convert to webp
    );
    if (empty($uploadedImages)) {
    echo $this->response_message([
        'code' => 400,
        'msg'  => "No image uploaded or image processing failed!"
    ]);
    return;
        }

    $galleryImgPath = $uploadedImages[0];

    // Insert into DB
    $insertData = [
        'gallery_name' => $data['gallery_name'],
        'image_path'  => $galleryImgPath
        ];

    if ($galleryModel->insert($insertData, false)) {
        echo $this->response_message([
            'code' => 200,
            'msg'  => "Gallery inserted successfully!"
        ]);
    } else {
        echo $this->response_message(false);
    }
}

public function updateGallery()
{

  
    helper('image');
    $galleryModel = new GalleryModel();
    $request = \Config\Services::request();
    $data = $request->getPost();

    $galleryId = $data['gallery_id'];

   

    // Check if gallery_name already exists for another record
    $nameCheck = $galleryModel
        ->where('gallery_id !=', $galleryId)
        ->where('gallery_name', $data['gallery_name'])
        ->first();

    if ($nameCheck) {
        echo $this->response_message([
            'code' => 400,
            'msg'  => "Gallery name already exists!"
        ]);
        return;
    }

    // Get existing gallery record
    $existingGallery = $galleryModel->find($galleryId);
    if (!$existingGallery) {
        echo $this->response_message([
            'code' => 400,
            'msg'  => "Gallery not found!"
        ]);
        return;
    }

    // Ensure folder exists
    $imgPath = "uploads/gallery_img/";
    if (!is_dir($imgPath)) {
        mkdir($imgPath, 0777, true);
    }

    // Process uploaded image
    $imageFiles = $this->request->getFiles();
    $uploadedImages = processUploadedImages(
        $imageFiles,
        'gallery_img', // form input name
        $imgPath,
        80, // quality
        true // convert to webp
    );

    // If new image uploaded → delete old image
    if (!empty($uploadedImages)) {
        $newImagePath = $uploadedImages[0];

        // Delete old file if exists
        if (!empty($existingGallery['image_path']) && file_exists($existingGallery['image_path'])) {
            unlink($existingGallery['image_path']);
        }
    } else {
        // No new image uploaded → keep old image path
        $newImagePath = $existingGallery['image_path'];
    }

    // Prepare update data
    $updateData = [
        'gallery_id'   => $galleryId,
        'gallery_name' => $data['gallery_name'],
        'image_path'   => $newImagePath
    ];

    // Update record
    if ($galleryModel->save($updateData)) {
        echo $this->response_message([
            'code' => 200,
            'msg'  => "Gallery updated successfully!"
        ]);
    } else {
        echo $this->response_message(false);
    }
}

public function deleteGallery()
{
    $galleryModel = new GalleryModel();
    $request = \Config\Services::request();

    

    $galleryId = $request->getPost('gallery_id');

    // Get the gallery record
    $gallery = $galleryModel->find($galleryId);
    if (!$gallery) {
        echo $this->response_message([
            'code' => 400,
            'msg'  => "Gallery not found!"
        ]);
        return;
    }

    // Delete image file if it exists
    if (!empty($gallery['image_path']) && file_exists($gallery['image_path'])) {
        unlink($gallery['image_path']);
    }

    // Delete record from DB
    if ($galleryModel->delete($galleryId)) {
        echo $this->response_message([
            'code' => 200,
            'msg'  => "Gallery deleted successfully!"
        ]);
    } else {
        echo $this->response_message(false);
    }
}


}