<?php

//initiate the session (must be the first statement in the document)
session_start();

//linked files
require_once 'dbConnect.php';

//DEBUG
$custID = '1';

$output = "3There was a problem creating the item.  Please try again.";

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//get values
if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    
    if (isset($_POST['item_num'])){

        $itemNum = $_POST['item_num'];
        $itemDesc = $_POST['item_desc'];
        $itemSta = $_POST['item_sta'];
        $itemWf = $_POST['item_wf'];

        $stmt = $conn->prepare("INSERT INTO ITM (ITM_CUS_id, ITM_num, ITM_desc, ITM_status, ITM_WFL_id) VALUES (?,?,?,?,?)");
        $stmt->bind_param("isssi", $custID, $itemNum, $itemDesc, $itemSta, $itemWf);

       // $stmt->execute();

        //echo  $custID . "_" . $itemNum . "_" . $itemDesc . "_" . $itemSta . "_" . $itemWf;

        if($stmt->execute()){

            $output = "<div class='contentTitle'>
                    Add New Item
                </div>
                <div class='contentHolder'>
                    <table class='inputTable'>
                        <tr>
                            <th>
                                $itemNum Created.
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <div class='buttonDiv' onclick='addNewItem()'>Add Another New Item</div>
                            </th>
                        </tr>
                    </table>
                </div>";
        } else {

            $output = "1There was a problem creating the item.  Please try again.";
        }

    } else {

        $output = "2There was a problem creating the item.  Please try again.";
    }

}

echo $output;

?>