<?php

namespace App\Controllers;
use CodeIgniter\Files\File;

use App\Models\EnquiriesModel;

class Workflow_Enquiries extends BaseController
{

    public function __construct()
    {
        
    }


public function getEnquiries()
{
    $draw   = (int) ($this->request->getGet('draw') ?? 1);
    $start  = (int) ($this->request->getGet('start') ?? 0);
    $length = $this->request->getGet('length'); // don't cast yet, so we can check if null
    $search = $this->request->getGet('search');

    $enquiriesModel = new EnquiriesModel();
    $enquiriesModel->where('flag', 1);

    if (!empty($search)) {
        $enquiriesModel->groupStart()
            ->like('name', $search)
            ->orlike('email', $search)
            ->orlike('subject', $search)
            ->orlike('message', $search)
            ->groupEnd();
    }

    // Get total filtered records count
    $totalRecords = $enquiriesModel->countAllResults(false);

    // Add order by created_at descending
    $enquiriesModel->orderBy('created_at', 'DESC');

    // If length is defined and not -1, apply limit, else fetch all
    if (!empty($length) && (int)$length !== -1) {
        $rows = $enquiriesModel->findAll((int)$length, $start);
    } else {
        $rows = $enquiriesModel->findAll(); // get all brands
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




    

public function deleteEnquiries()
{
    $enquiriesModel = new EnquiriesModel();
    $request = \Config\Services::request();

    $enquiryId = $request->getPost('enquiries_id'); // ✅ Correct ID name

    if (!$enquiryId) {
        echo $this->response_message([
            'code' => 400,
            'msg'  => "Enquiry ID is required!"
        ]);
        return;
    }

    // Update only where enquiry_id matches and flag = 1
    $update = $enquiriesModel
        ->where('id', $enquiryId)
        ->set(['flag' => 0])
        ->update();

    if ($update && $enquiriesModel->db->affectedRows()) {
        echo $this->response_message([
            'code' => 200,
            'msg'  => "Enquiry deleted successfully!"
        ]);
    } else {
        echo $this->response_message([
            'code' => 404,
            'msg'  => "No matching record found or already deleted!"
        ]);
    }
}


}