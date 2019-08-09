<?php 
session_start();
require_once'connection.php';


if (isset($_POST['currentData'])) {
    
    //parse data
    $oldData = json_decode($_POST['currentData'], true);
    
    $conn = Connection::get();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //query to select the last prices
    $checkForChange = "SELECT * FROM `last_prices` ORDER BY `company_id` ASC";
    
    $newData = array();
    $flag = false;
    $i = 0;
    //loop through last prices, add to $newData array and check for change
    foreach ($conn->query($checkForChange) as $row){
        
        array_push($newData, $row['last_price']);
        if($row['last_price'] != $oldData[$i]['price']){$flag = true;}
        $i++;
    }
    
    //if we have change, send the last prices back. if not, return "no change"
    if($flag){
        echo json_encode($newData);
    }
    else{
        echo "no change";
    }
    
}
?>