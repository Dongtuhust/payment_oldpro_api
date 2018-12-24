<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
// include database and object files
include_once '../config/connectdb.php';
include_once '../objects/orders.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare order object
$order = new Order($db);
 
// set ID property of order to be edited
$order->order_id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of order to be edited
$order->readOne();
 
// create array
$order_arr = array(
    "order_id" => $order->order_id,
    "seller" => $seller,
    "customer_name" => $order->customer_name,
    "total_money" => $order->total_money,
    "customer_phone" => $order->customer_phone,
    "customer_add" => $order->customer_add,
    "payment" => $order->payment,
    "gender" => $order->gender,
    "order_date" => $order->order_date,
    "note" => $order->note,
    "status" => $order->status,
    "receive_date" => $order->receive_date
);
 
// make it json format
print_r(json_encode($order_arr));