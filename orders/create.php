<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// get database connection
include_once '../config/connectdb.php';
 
// instantiate order object
include_once '../objects/orders.php';


// get data rabbitmq
$connection = new AMQPConnection('35.198.247.32', 31234, 'guest', 'guest');
$channel    = $connection->channel();
$channel->queue_declare('order_old_queue', false, false, false, false);


$callback = function ($msg) {
	$data =  $msg->body;
	$database = new Database();
	$db = $database->getConnection();
	$data = json_decode($data);
	$order = new Order($db);
// set order property values
	$order->order_id = $data->order_id;
	$order->seller = $data->seller;
	$order->customer_name = $data->customer_name;
	$order->gender = $data->gender;
	$order->customer_add = $data->address;
	$order->customer_phone = $data->phone;
	$order->total_money = $data->total_money;
	$order->note = $data->note;
	$order->payment = $data->payment;
	$order->status = "Đang chờ";
	$order->order_date = $data->order_date;
// create the order
	if($order->create()){
	    echo '{';
	        echo '"message": "Tạo mới một đơn hàng thành công"';
	    echo '}';
	    return true;
	}
	 
	// if unable to create the order, tell the user
	else{
	    echo '{';
	        echo '"message": "Tạo mới thất bại"';
	    echo '}';
	    return false;
	}
	
};

$channel->basic_consume('order_old_queue', '', false, true, false, false, $callback);

while (count($channel->callbacks)) {
    $channel->wait();
    if ($channel->callbacks) {
    	break;
    }
}
?>