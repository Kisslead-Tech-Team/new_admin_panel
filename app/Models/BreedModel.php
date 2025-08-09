<?php
namespace App\Models;
use CodeIgniter\Model;

class BreedModel extends Model{
    protected $table = 'breed_tbl';
    protected $primaryKey = 'breed_id';
    protected $allowedFields = [
        'pet_id',
        'breed_name',
        'flag'
    ];
}