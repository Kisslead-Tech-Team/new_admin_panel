<?php
namespace App\Models;
use CodeIgniter\Model;

class ToolsBrandModel extends Model{
    protected $table = 'tools_brand_tbl';
    protected $primaryKey = 'brand_id';
    protected $allowedFields = [
        'brand_name',
        'url',
        'logo_path',
        'flag'
    ];
}