<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/connectdb.php';
include_once '../objects/orders.php';
 
// khởi tạo  database , và kết nối 
$database = new Database();
$db = $database->getConnection();
 
// Khởi tạo object order
$order = new Order($db);
 
// query order
$stmt = $order->read();
$num = $stmt->rowCount();
 
// kiểm tra nếu nhiều hơn 1 bản ghi được tìm thấy
if($num>0){
 
    // orders array
    $orders_arr=array();
    $orders_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['customer_name'] to
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
 
    echo json_encode($orders_arr);
}
 
else{
    echo json_encode(
        array("message" => "Không tìm thấy đơn hàng nào.")
    );
}
?>