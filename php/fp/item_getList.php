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

        case "%":

            $whereClause = "";
            $status = "All Items";
        
            break;

        case "String":
        
            $searchTerm = $_POST['search_term'];

            $status = "Items Containing Search Term: \"$searchTerm\"";
            
            $searchTerm = "%" . $searchTerm . "%";

            $whereClause = "AND (ITM_num Like '$searchTerm' OR ITM_desc Like '$searchTerm')";

        
            break;

        default:
        
            $whereClause = "AND ITM_status Like '$searchType'";
            $status = $searchType . " Items";

            break;

    }

    $output = "<div class='contentTitle'>
                Items List :  $status
            </div>
            <div class='contentHolder'>";

    $sql = "SELECT DISTINCT ITM.ITM_id, ITM.ITM_num, ITM.ITM_desc, ITM.ITM_status, WFL.WFL_num
            FROM ITM
            LEFT JOIN WFL ON ITM.ITM_WFL_id = WFL.WFL_id
            WHERE ITM_CUS_id = '$custID' $whereClause";

    $result = mysqli_query($conn,$sql);

    if($result){

    if($result->num_rows != 0){

        $output .= "<table id='itemList' class='listTable tablesorter'>
                        <thead>
                        <tr>
                            <th>Item</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Default Workflow</th>
                        </tr>
                        </thead>
                        <tbody>";
        
        while($row = $result->fetch_assoc()){

            $itemID = $row['ITM_id'];
            $itemNum = $row['ITM_num'];
            $itemDesc = $row['ITM_desc'];
            $itemSta = $row['ITM_status'];
            $itemWf = $row['WFL_num'];

            if($itemWf == 0){

                $itemWf = 'none';
            }

            $output .= "<tr onclick='getItemDetails($itemID)'>
                    <td>$itemNum</td>
                    <td>$itemDesc</td>
                    <td>$itemSta</td>
                    <td>$itemWf</td>
                </tr>";
        }
    } else {

        $output .= "No items found.";
    }

}

$output .= "</tbody></table></div></div>";


} else {
    
    $output = "There was a problem retrieving items</div></div>";
}

echo $output;
?>