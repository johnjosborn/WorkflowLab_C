<?php

//verify ownership of workflow
function verifyWfCust($wfl, $custID, $conn){

    $sql = "SELECT WFL_id
    FROM WFL
    WHERE WFL_CUS_id = '$custID' AND WFL_id = $wfl";

    $result = mysqli_query($conn,$sql);

    if($result){

        if($result->num_rows != 0){

            //match
            return true;
        } 
    }
    return false;
}

//verify ownership of workflow
function verifyOpCust($op, $custID, $conn){
    
        $sql = "SELECT OPS_id
        FROM OPS
        WHERE OPS_CUS_id = '$custID' AND OPS_id = $op";
    
        $result = mysqli_query($conn,$sql);
    
        if($result){
    
            if($result->num_rows != 0){
    
                //match
                return true;
            } 
        }
        return false;   
    }

?>