<?php
namespace App\Models;
use CodeIgniter\Model;

class EnquiriesModel extends Model{
    protected $table = 'enquiries_tbl';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name',
        'email',
        'subject',
        'message',
        'flag'
    ];
}