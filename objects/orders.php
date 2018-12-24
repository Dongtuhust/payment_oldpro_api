<?php
class Order{
 
    // database connection and table name
    private $conn;
    private $table_name = "orders_old_product";
 
    // object properties
    public $order_id;
    public $seller;
    public $customer_name;
    public $customer_add;
    public $customer_phone;
    public $total_money;
    public $order_date;
    public $payment;
    public $gender;
    public $note;
    public $status;
    public $receive_date;

 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read orders
    function read(){
 
        // select all query
        $query = "SELECT * FROM orders_old_product";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }

    // create order
    function create(){
     
        // query to insert record
        $query = "INSERT INTO " . $this->table_name . "(customer_name,seller,status,total_money,customer_add,customer_phone,gender,note,order_date,payment) values (:customer_name,:seller,:status,:total_money,:customer_add,:customer_phone,:gender,:note,:order_date,:payment) ";
     
        // prepare query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->customer_name=htmlspecialchars(strip_tags($this->customer_name));
        $this->seller=htmlspecialchars(strip_tags($this->seller));
        $this->status=htmlspecialchars(strip_tags($this->status));
        $this->total_money=htmlspecialchars(strip_tags($this->total_money));
        $this->customer_add=htmlspecialchars(strip_tags($this->customer_add));
        $this->customer_phone=htmlspecialchars(strip_tags($this->customer_phone));
        $this->gender=htmlspecialchars(strip_tags($this->gender));
        $this->note=htmlspecialchars(strip_tags($this->note));
        $this->payment=htmlspecialchars(strip_tags($this->payment));
        $this->order_date=htmlspecialchars(strip_tags($this->order_date));
     
        // bind values
        $stmt->bindParam(":customer_name", $this->customer_name);
        $stmt->bindParam(":seller", $this->seller);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":total_money", $this->total_money);
        $stmt->bindParam(":customer_add", $this->customer_add);
        $stmt->bindParam(":customer_phone", $this->customer_phone);
        $stmt->bindParam(":gender", $this->gender);
        $stmt->bindParam(":note", $this->note);
        $stmt->bindParam(":payment", $this->payment);
        $stmt->bindParam(":order_date", $this->order_date);
     
        // execute query
        if($stmt->execute()){
            return true;
        }
     
        return false;
         
    }


    // used when filling up the update order form
    function readOne(){
     
        // query to read single record
        $query = "SELECT
                   *
                FROM
                    orders_old_product u
                WHERE
                    u.order_id = ?
                LIMIT
                    0,1";
     
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind order_id of order to be updated
        $stmt->bindParam(1, $this->order_id);
     
        // execute query
        $stmt->execute();
     
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
     
        // set values to object properties
        $this->customer_name=$row['customer_name'];
        $this->seller=$row['seller'];
        $this->status=$row['status'];
        $this->customer_add=$row['customer_add'];
        $this->customer_phone=$row['customer_phone'];
        $this->gender=$row['gender'];
        $this->note=$row['note'];
        $this->payment=$row['payment'];
        $this->order_date=$row['order_date'];
    }



    // update the order
    function update(){
     
        // update query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    customer_name=:customer_name, status=:status, customer_add=:customer_add,customer_phone=:customer_phone,receive_date=:receive_date,gender:=gender,note=:note, total_money=:total_money,payment:=payment
                WHERE
                    order_id = :order_id";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->customer_name=htmlspecialchars(strip_tags($this->customer_name));
        $this->status=htmlspecialchars(strip_tags($this->status));
        $this->customer_add=htmlspecialchars(strip_tags($this->customer_add));
        $this->customer_phone=htmlspecialchars(strip_tags($this->customer_phone));
        $this->gender=htmlspecialchars(strip_tags($this->gender));
        $this->note=htmlspecialchars(strip_tags($this->note));
        $this->payment=htmlspecialchars(strip_tags($this->payment));
        $this->total_money=htmlspecialchars(strip_tags($this->total_money));
        $this->receive_date=htmlspecialchars(strip_tags($this->receive_date));
     
        // bind values
        $stmt->bindParam(":customer_name", $this->customer_name);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":customer_add", $this->customer_add);
        $stmt->bindParam(":customer_phone", $this->customer_phone);
        $stmt->bindParam(":gender", $this->gender);
        $stmt->bindParam(":note", $this->note);
        $stmt->bindParam(":payment", $this->payment);
        $stmt->bindParam(":total_money", $this->total_money);
        $stmt->bindParam(":receive_date", $this->receive_date);
     
        // execute the query
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }


    // delete the order
    function delete(){
     
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE order_id = ?";
     
        // prepare query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->order_id=htmlspecialchars(strip_tags($this->order_id));
     
        // bind order_id of record to delete
        $stmt->bindParam(1, $this->order_id);
     
        // execute query
        if($stmt->execute()){
            return true;
        }
     
        return false;
         
    }


    // search orders
    function search($keywords){
     
        // select all query
        $query = "SELECT
                   *
                FROM
                    " . $this->table_name . " u
                WHERE
                    u.customer_name LIKE ? ";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $keywords=htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";
     
        // bind
        $stmt->bindParam(1, $keywords);

        // execute query
        $stmt->execute();
     
        return $stmt;
    }


        // read orders with pagination
    public function readPaging($from_record_num, $records_per_page){
     
        // select query
        $query = "SELECT
                   *
                FROM  $this->table_name LIMIT ?, ?";
     
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind variable values
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
     
        // execute query
        $stmt->execute();
     
        // return values from database
        return $stmt;
    }

    // used for paging orders
    public function count(){
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
     
        return $row['total_rows'];
    }
}