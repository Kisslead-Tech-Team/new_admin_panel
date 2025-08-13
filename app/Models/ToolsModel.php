<?php
namespace App\Models;
use CodeIgniter\Model;

class ToolsModel extends Model{
    protected $table = 'tools_tbl';
    protected $primaryKey = 'tools_id';
    protected $allowedFields = [
        'brand_id',
        'category_id',
        'tools_name',
        'tools_url',
        'tools_description',
        'tools_brochure',
        'flag'
       
    ];
}