<?php
namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model{
    protected $table = 'user_tbl';
    protected $primaryKey = 'user_id';
    protected $allowedFields = ['user_id'];
}