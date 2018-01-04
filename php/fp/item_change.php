<?php

//initiate the session (must be the first statement in the document)
session_start();

//linked files
require_once 'dbConnect.php';

//DEBUG
$custID = '1';

$output = "There was a problem saving changes to this item.  Please try again.";

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//get values
if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    
    if (isset($_POST['item_id'])){

        $itemID = $_POST['item_id'];
        $itemNum = $_POST['item_num'];
        $itemDesc = $_POST['item_desc'];
        $itemSta = $_POST['item_sta'];
        $itemWf = $_POST['item_wf'];

        $stmt = $conn->prepare("UPDATE ITM  
                                SET ITM_num = ?, ITM_desc = ?, ITM_status = ?, ITM_WFL_id = ? 
                                WHERE ITM_CUS_id = $custID AND ITM_id =  $itemID");

        $stmt->bind_param("sssi", $itemNum, $itemDesc, $itemSta, $itemWf);

        if($stmt->execute()){

            $output = "$itemNum Updated.";

        } else {

            $output = "There was a problem creating the item.  Please try again.";
        }

    } else {

        $output = "There was a problem creating the item.  Please try again.";
    }

}

echo $output;

?>