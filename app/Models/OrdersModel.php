<?php
namespace App\Models;
use CodeIgniter\Model;

class OrdersModel extends Model{
    protected $table = 'orders_tbl';
    protected $primaryKey = 'order_id';
    protected $allowedFields = [
        'tools_id',
        'name',
        'email',
        'contact',
        'quantity',
        'flag'
    ];
}