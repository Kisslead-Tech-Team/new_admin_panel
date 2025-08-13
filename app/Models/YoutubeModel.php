<?php
namespace App\Models;
use CodeIgniter\Model;

class YoutubeModel extends Model{
    protected $table = 'youtube_tbl';
    protected $primaryKey = 'youtube_id';
    protected $allowedFields = [
        'youtube_name',
        'youtube_url'
    ];
}