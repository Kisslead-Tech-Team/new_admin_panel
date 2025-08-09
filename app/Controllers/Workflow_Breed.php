<?php

namespace App\Controllers;
use CodeIgniter\Files\File;

use App\Models\BreedModel;

class Workflow_Breed extends BaseController
{

    protected $db;

    protected $model;

    public function __construct()
    {
        $this->db = db_connect();
        $this->model = new BreedModel;
    }

    public function getBreed(){

        $builder = $this->db->table('breed_tbl');
        $builder->select('*');
        $builder->join('pet_tbl', 'pet_tbl.pet_id = breed_tbl.pet_id');
        $builder->where('breed_tbl.flag=1');
        $data = $builder->get();
        $data =$data->getResult(); 
if($data){
echo $this->response_message([
    'code' => 200,
    'data' => json_encode($data)
]); return;
}
echo $this->response_message(false, false);
    }


    // public function getSpecificBrand(){

    //     $request = \Config\Services::request();
    //     $data =  $request->getPost();

    //     $response = $this->model->where('product_category_id', $data['product_category_id'])->first();
    //     echo json_encode($response);
    // }



    public function insertBreed(){
        $request = \Config\Services::request();
        $data =  $request->getPost();
        $filepath = '';


        $breed_check = $this->model->where(array('pet_id' => $data['pet_id'], 'breed_name' => $data['breed_name'], 'flag' => 1 ))->first();

        if(!$breed_check){
        if($data && $this->model->insert($data, false)){
            echo $this->response_message([
                'code' => 200,
                'msg' => "Breed inserted successfully!"
            ]); return;
        }

        echo $this->response_message(false, false);

    }
    else{
        echo $this->response_message([
            'code' => 400,
            'msg' => "Breed name is already there try another name!"
        ]); return;

    }
    }
    

    
    public function updateBreed(){
        $request = \Config\Services::request();
        $data =  $request->getPost();
        $filepath = '';
        $updatedData=[];


        $breed_name_check = $this->model->where(array('breed_id !=' => $data['breed_id'], 'pet_id' => $data['pet_id'], 'breed_name' => $data['breed_name'], 'flag' => 1 ))->first();

        if(!$breed_name_check){


        $breed_check = $this->model->where('breed_id', $data['breed_id'])->first();
        if($breed_check){
            $updatedData['breed_id']=$data['breed_id'];
            $updatedData['pet_id']=$data['pet_id'];
            $updatedData['breed_name']=$data['breed_name'];
            $update = $this->model->save($updatedData);
            if($data && $update){
                if($this->model->db->affectedRows()){
                    echo $this->response_message([
                        'code' => 200,
                        'msg' => "Pet updated successfully!"
                    ]); return;
                } else{
                    echo $this->response_message([
                        'code' => 400,
                        'msg' => "No changes"
                    ]); return;
                }
            }
            echo $this->response_message(false, false);
        }
        echo $this->response_message(false, false);

    }else{
        echo $this->response_message([
            'code' => 400,
            'msg' => "Breed name is already there try another name!"
        ]); return;
    }
    }

    public function deleteBreed(){

       
        $request = \Config\Services::request();
        $data =  $request->getPost();
        $data['flag']=0;
        if($data && $this->model->save($data)){
            echo $this->response_message([
                'code' => 200,
                'msg' => "Product Type deleted successfully!"
            ]); return;
        }

        echo $this->response_message(false, false);
    }
}