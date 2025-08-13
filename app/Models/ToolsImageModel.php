<?php
namespace App\Models;

use CodeIgniter\Model;

class ToolsImageModel extends Model
{
    protected $table      = 'tools_img_tbl';
    protected $primaryKey = 'tools_img_id'; // change if different
    protected $allowedFields = [
        'tools_id',
        'image_path'
    ];
}