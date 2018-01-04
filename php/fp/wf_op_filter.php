<?php

//initiate the session (must be the first statement in the document)
session_start();

//linked files
require_once 'dbConnect.php';
//require_once 'logInValidation.php'; //$userID, $custID

//DEBUG
$custID = '1';

//default declaration values

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['filter'])){

    $filter = $_POST['filter'];
    
    //All the available steps
    $sql = "SELECT OPS_id, OPS_title, OPS_desc
            FROM OPS
            WHERE (OPS_CUS_id = '$custID' OR OPS_CUS_id = '99') AND OPS_type Like '$filter'
            ORDER By OPS_type, OPS_title";

    $result = mysqli_query($conn,$sql);

    if($result){

        $ops = "";

        if($result->num_rows != 0){

        while($row = $result->fetch_assoc()){

            $newID =  $row['OPS_id'] . 'n';
            $newTitle = $row['OPS_title'];
            $newDesc = $row['OPS_desc'];

            $ops .= "<div class='s_panel availOp' id='opNum=$newID'>
                        <div class=''>
                            <div class='orderBox'></div>
                            <div class='titleBox'>$newTitle | $newDesc</div>
                        </div>
                    </div>";
        }
    }

        $ops .= "</div>";

    } else {

        $ops = "Bad query for available ops.";
    }

    $output = $ops;

} else {
    $output = "Error posting data";
}
echo $output;

?>