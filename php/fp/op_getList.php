<?php

//initiate the session (must be the first statement in the document)
session_start();

//linked files
require_once 'dbConnect.php';

//DEBUG
$custID = '1';

if (isset($_POST['search_type'])){

    $searchType = $_POST['search_type'];

    switch ($searchType){

        case "Type":

            $searchTerm = $_POST['search_term'];

            $whereClause = "AND OPS_type = '$searchTerm'";

            $status = "By Type";

            break;

        case "String":

            $searchTerm = $_POST['search_term'];

            $status = "Steps Containing Search Term: \"$searchTerm\"";
            
            $searchTerm = "%" . $searchTerm . "%";

            $whereClause = "AND (OPS_title Like '$searchTerm' OR OPS_desc Like '$searchTerm' OR OPS_detail Like '$searchTerm')";

            break;

        case "%":

            $whereClause = "";

            $status = "All Steps";
        
            break;

        default:
        
            $whereClause = "AND OPS_status Like '$searchType'";

            $status = "$searchType Steps";

            break;

    }

    $output = "<div class='contentTitle'>
                    $status
                </div>
                <div class='contentHolder'>";

    $sql = "SELECT OPS_id, OPS_title, OPS_desc, OPS_detail, OPS_type, OPS_status
            FROM OPS
            WHERE OPS_CUS_id = '$custID' $whereClause";
        
    $result = mysqli_query($conn,$sql);

    if($result){

    if($result->num_rows != 0){

        $output .= "<table id='opList' class='listTable tablesorter'>
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Detail</th>
                <th>Type</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>";

        while($row = $result->fetch_assoc()){

            $opID = $row['OPS_id'];
            $opTitle = $row['OPS_title'];
            $opDesc = $row['OPS_desc'];
            $opDetail = $row['OPS_detail'];
            $opType = $row['OPS_type'];
            $opSta = $row['OPS_status'];

            $output .= "<tr onclick='editOp($opID)'>
                <td>$opTitle</td>
                <td>$opDesc</td>
                <td>$opDetail</td>
                <td>$opType</td>
                <td>$opSta</td>
                </tr>";
        }

        $output .= "</tbody></table></div></div>";
    } else {

        $output .= "No matching Steps found.";
    }

} else {

    $output .= "No matching Steps found.";
}

$output .= "</div></div>";

echo $output;

} else {

    echo "Post not set";
}

?>