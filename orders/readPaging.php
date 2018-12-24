<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/paging_config.php';
include_once '../shared/utilities.php';
include_once '../config/connectdb.php';
include_once '../objects/orders.php';
 
// utilities
$utilities = new Utilities();
 
// instantiate database and order object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$order = new Order($db);
 
// query orders
$stmt = $order->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // orders array
    $orders_arr=array();
    $orders_arr["records"]=array();
    $orders_arr["paging"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $order_item=array(
            "order_id" => $order_id,
            "seller" => $seller,
            "customer_name" => $customer_name,
            "total_money" => html_entity_decode($total_money),
            "customer_phone" => $customer_phone,
            "customer_add" => $customer_add,
            "payment" => $payment,
            "gender" => $gender,
            "order_date" => $order_date,
            "note" => $note,
            "status" => $status,
            "receive_date" => $receive_date
        );
 
        array_push($orders_arr["records"], $order_item);
    }
 
 
    // include paging
    $total_rows=$order->count();
    $page_url="{$home_url}orders/readPaging.php?";
    $paging=$utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    $orders_arr["paging"]=$paging;
 
    echo json_encode($orders_arr);
}
 
else{
    echo json_encode(
        array("message" => "Không tìm thấy đơn hàng nào cả")
    );
}
?>