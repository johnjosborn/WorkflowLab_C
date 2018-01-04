<?php

//initiate the session (must be the first statement in the document)
session_start();

//linked files
require_once 'fp/dbConnect.php';

//DEBUG
$custID = '1';

$sql = "SELECT WFL_id, WFL_num, WFL_status, ITM_num, ITM_desc, WKO_name
    FROM WFL
    INNER JOIN WKO ON WFL_WKO_id = WKO.WKO_id
    INNER JOIN ITM ON ITM.ITM_id = WKO.WKO_ITM_id
    WHERE WFL_CUS_id = '$custID'";

$result = mysqli_query($conn,$sql);

if($result){

    if($result->num_rows != 0){
        
        while($row = $result->fetch_assoc()){
            
            echo $row['ITM_num'];
            echo "<br>";
            echo $row['WKO_name'];
            echo "<br>";
            echo "<br>";
            
        }
        
    }
} else {
    echo "No result";
}

?>