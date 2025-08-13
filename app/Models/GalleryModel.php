<?php
namespace App\Models;
use CodeIgniter\Model;

class GalleryModel extends Model{
    protected $table = 'gallery_tbl';
    protected $primaryKey = 'gallery_id';
    protected $allowedFields = [
        'gallery_name',
        'image_path'
    ];
}