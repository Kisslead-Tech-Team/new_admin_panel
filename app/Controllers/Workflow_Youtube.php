<?php

namespace App\Controllers;
use CodeIgniter\Files\File;

use App\Models\YoutubeModel;

class Workflow_Youtube extends BaseController
{

    public function __construct()
    {
        
    }


public function getYoutube()
{
    $draw   = (int) ($this->request->getGet('draw') ?? 1);
    $start  = (int) ($this->request->getGet('start') ?? 0);
    $length = $this->request->getGet('length'); // don't cast yet, so we can check if null
    $search = $this->request->getGet('search');

    $youtubeModel = new YoutubeModel();
    $youtubeModel->where('flag', 1);

    if (!empty($search)) {
        $youtubeModel->groupStart()
            ->like('youtube_name', $search)
            ->orlike('youtube_url', $search)
            ->groupEnd();
    }

    // Get total filtered records count
    $totalRecords = $youtubeModel->countAllResults(false);

    // Add order by created_at descending
    $youtubeModel->orderBy('created_at', 'DESC');

    // If length is defined and not -1, apply limit, else fetch all
    if (!empty($length) && (int)$length !== -1) {
        $rows = $youtubeModel->findAll((int)$length, $start);
    } else {
        $rows = $youtubeModel->findAll(); // get all brands
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



public function insertYoutube()
{
    $youtubeModel = new YoutubeModel();
    $request = \Config\Services::request();
    $data = $request->getPost(); // should contain brand_name and brand_id

    // Check if brand_name already exists (and flag is 1)
    $brandCheck = $youtubeModel->where([
        'youtube_url' => $data['youtube_url'],
        'flag'       => 1
    ])->first();

    if (!$brandCheck) {
        if ($data && $youtubeModel->insert($data, false)) {
            echo $this->response_message([
                'code' => 200,
                'msg'  => "URL inserted successfully!"
            ]);
            return;
        }

        // Insert failed
        echo $this->response_message(false);
    } else {
        // Brand already exists
        echo $this->response_message([
            'code' => 400,
            'msg'  => "URL already exists! Try another name."
        ]);
        return;
    }
}
    

public function updateYoutube()
{
    $youtubeModel = new YoutubeModel();
    $request = \Config\Services::request();
    $data = $request->getPost();


    // Check if another brand already has this name (excluding current brand)
    $youtube_url_check = $youtubeModel
        ->where('youtube_id !=', $data['youtube_id'])
        ->where('youtube_url', $data['youtube_url'])
        ->where('flag', 1)
        ->first();

    if (!$youtube_url_check) {
        // Check if brand exists
        $url_check = $youtubeModel->where('youtube_id', $data['youtube_id'])->first();
        if ($url_check) {
            $update = $youtubeModel->save($data);
            if ($data && $update) {
                if ($youtubeModel->db->affectedRows()) {
                    echo $this->response_message([
                        'code' => 200,
                        'msg'  => "Youtube URL updated successfully!"
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
            'msg'  => "Youtube url is already there, try another name!"
        ]);
        return;
    }
}

public function deleteYoutube()
{
    $youtubeModel = new YoutubeModel();
    $request = \Config\Services::request();

    $youtubeId = $request->getPost('youtube_id');

    if (!$youtubeId) {
        echo $this->response_message([
            'code' => 400,
            'msg'  => "YouTube ID is required!"
        ]);
        return;
    }

    // Try deleting the record
    $delete = $youtubeModel->delete($youtubeId);

    if ($delete) {
        echo $this->response_message([
            'code' => 200,
            'msg'  => "YouTube entry deleted successfully!"
        ]);
    } else {
        echo $this->response_message(false);
    }
}


}