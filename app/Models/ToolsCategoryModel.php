<?php
namespace App\Models;
use CodeIgniter\Model;

class ToolsCategoryModel extends Model{
    protected $table = 'tools_category_tbl';
    protected $primaryKey = 'category_id';
    protected $allowedFields = [
        'category_name',
        'category_url',
        'flag'
    ];
}