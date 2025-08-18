<?php

namespace App\Controllers;
use CodeIgniter\Files\File;

use App\Models\OrdersModel;

class Workflow_Orders extends BaseController
{

    public function __construct()
    {
        
    }


public function getOrders()
{
    $draw   = (int) ($this->request->getGet('draw') ?? 1);
    $start  = (int) ($this->request->getGet('start') ?? 0);
    $length = $this->request->getGet('length'); 
    $searchParam = $this->request->getGet('search');
    $search = $searchParam['value'] ?? null; // ✅ extract actual string

    $ordersModel = new OrdersModel();

    // ✅ Base query with joins
    $ordersModel
        ->select('orders_tbl.*, tools_tbl.tools_name')
        ->join('tools_tbl', 'tools_tbl.tools_id = orders_tbl.tools_id', 'left')
        ->where('orders_tbl.flag !=', 0);

    // ✅ Apply search filter
    if (!empty($search)) {
        $ordersModel->groupStart()
            ->like('orders_tbl.name', $search)
            ->orLike('orders_tbl.email', $search)
            ->orLike('orders_tbl.contact', $search)
            ->orLike('orders_tbl.quantity', $search)
            ->orLike('tools_tbl.tools_name', $search)
            ->groupEnd();
    }

    // ✅ Count with current filters (false = don’t reset query)
    $totalRecords = $ordersModel->countAllResults(false);

    // ✅ Order & paginate
    $ordersModel->orderBy('orders_tbl.created_at', 'DESC');

    if (!empty($length) && (int)$length !== -1) {
        $rows = $ordersModel->findAll((int)$length, $start);
    } else {
        $rows = $ordersModel->findAll();
    }

    // ✅ Prepare DataTables response
    $responseData = [
        'draw' => $draw,
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $totalRecords,
        'data' => $rows
    ];

    return $this->response_message([
        'code' => 200,
        'data' => $responseData
    ]);
}



public function updateOrders()
{
    $ordersModel = new OrdersModel();
    $request = \Config\Services::request();
    $data = $request->getPost();


    // Ensure order_id and flag are passed
    if (empty($data['order_id']) || empty($data['status'])) {
        echo $this->response_message([
            'code' => 400,
            'msg'  => "Order ID and Flag are required!"
        ]);
        return;
    }

    // Check if order exists
    $order = $ordersModel->where('order_id', $data['order_id'])->first();
    if (!$order) {
        echo $this->response_message([
            'code' => 404,
            'msg'  => "Order not found!"
        ]);
        return;
    }

    // Update only the flag
    $update = $ordersModel->update($data['order_id'], [
        'flag' => $data['status']
    ]);

    if ($update) {
        if ($ordersModel->db->affectedRows()) {
            echo $this->response_message([
                'code' => 200,
                'msg'  => "Order status updated successfully!"
            ]);
        } else {
            echo $this->response_message([
                'code' => 400,
                'msg'  => "No changes made."
            ]);
        }
    } else {
        echo $this->response_message([
            'code' => 500,
            'msg'  => "Failed to update order status."
        ]);
    }
}



    

public function deleteOrders()
{
    $ordersModel = new OrdersModel();
    $request = \Config\Services::request();

    $orderId = $request->getPost('order_id'); // ✅ Correct ID name

    if (!$orderId) {
        echo $this->response_message([
            'code' => 400,
            'msg'  => "Order ID is required!"
        ]);
        return;
    }

    // Update only where enquiry_id matches and flag = 1
    $update = $ordersModel
        ->where('order_id', $orderId)
        ->set(['flag' => 0])
        ->update();

    if ($update && $ordersModel->db->affectedRows()) {
        echo $this->response_message([
            'code' => 200,
            'msg'  => "Order deleted successfully!"
        ]);
    } else {
        echo $this->response_message([
            'code' => 404,
            'msg'  => "No matching record found or already deleted!"
        ]);
    }
}


}