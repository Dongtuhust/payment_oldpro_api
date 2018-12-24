<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// include database and object files
include_once '../config/connectdb.php';
include_once '../objects/orders.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare order object
$order = new Order($db);
 
// get id of order to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of order to be edited
$order->order_id = $data->id;
 
// set order property values
$order->seller = $data->seller;
$order->customer_name = $data->customer_name;
$order->customer_phone = $data->customer_phone;
$order->customer_add = $data->customer_add;
$order->total_money = $data->total_money;
$order->payment = $data->payment;
$order->gender = $data->gender;
$order->receive_date = date('Y-m-d H:i:s');
$order->note = $data->note;
$order->status = $data->status;
 
// update the order
if($order->update()){
    echo '{';
        echo '"message": "Cập nhật thành công đơn hàng"';
    echo '}';
}
 
// if unable to update the order, tell the order
else{
    echo '{';
        echo '"message": "Cập nhật thất bại"';
    echo '}';
}
?>