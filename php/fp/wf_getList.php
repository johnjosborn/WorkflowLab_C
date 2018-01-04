<?php

//initiate the session (must be the first statement in the document)
session_start();

//linked files
require_once 'dbConnect.php';

//DEBUG
$custID = '1';

$totalSteps = 0;
$compSteps = 0;
$workflowProgress = 0;
$status = "";

if (isset($_POST['search_type'])){

    $searchType = $_POST['search_type'];

    switch ($searchType){

        case "Item":

            $searchTerm = $_POST['search_term'];

            $whereClause = "AND WFL_item = '$searchTerm'";

            $status = "By Item";

            break;

        case "Group":
        
                    $searchTerm = $_POST['search_term'];
        
                    $whereClause = "AND WFL_group = '$searchTerm'";
        
                    $status = "By Group";
        
                    break;

        case "String":

            $searchTerm = $_POST['search_term'];

            $status = "Workflows Containing Search Term: \"$searchTerm\"";
            
            $searchTerm = "%" . $searchTerm . "%";

            $whereClause = "AND (WFL_item Like '$searchTerm' OR WFL_desc Like '$searchTerm' OR WFL_num Like '$searchTerm')";

        
            break;

        case "%":

            $whereClause = "AND WFL_status <> 'Template'";

            $status = "All Workflows";
        
            break;

        default:
        
            $whereClause = "AND WFL_status Like '$searchType'";

            $status = "$searchType Workflows";

            break;

    }

    $output = "<div class='contentTitle'>
                    $status
                </div>
                <div class='contentHolder'>";

    $sql = "SELECT WFL_id, WFL_num, WFL_status, WFL_item, WFL_desc, WFL_group
            FROM WFL
            WHERE WFL_CUS_id = '$custID' $whereClause";
        
$result = mysqli_query($conn,$sql);

if($result){

    if($result->num_rows != 0){

        $output .= "<table id='wfList' class='listTable tablesorter'>
        <thead>
            <tr>
                <th>Number</th>
                <th>Item</th>
                <th>Description</th>
                <th>Group</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>";

        while($row = $result->fetch_assoc()){

            $totalSteps = 0;
            $compSteps = 0;
            $workflowProgress = 0;
            $percentCompWidth = 0;
            $percentText = "Not Started";

            //Count completed steps
            $sql2 = "SELECT STP_id, STP_status
                    FROM STP
                    WHERE STP_WFL_id = " . $row['WFL_id'];
                    
            $result2 = mysqli_query($conn,$sql2);

            if($result2){

                if($result2->num_rows != 0){

                    while($row2 = $result2->fetch_assoc()){

                        $thisStatus = $row2['STP_status'];

                        $totalSteps++;

                        switch($thisStatus){

                            case "Complete":
                                $class = "stepComplete";
                                $compSteps++;
                            break;
                        }
                    }

                    if ($totalSteps != 0){
                        $workflowProgress = ($compSteps / $totalSteps) * 100;

                        $percentCompWidth = round($workflowProgress) . "%";

                        if($workflowProgress == 0){
                            
                            $percentText = "Not Started";

                        } else if($workflowProgress < 100){
                            
                            $percentText = round($workflowProgress) . "% Complete";

                        }  else{
                            
                            $percentText = "Complete";
                        }

                    } else {
                        $percentText = "Not Started";
                    }
                } else {
                    $percentText = "No Steps.";
                }
            }

            $group = $row['WFL_group'];

            if($group == "0"){
                $group = '';
            }

            $output .= "<tr onclick='openWorkflow(this)' id='" . $row['WFL_id'] . "'>
                <td>" . $row['WFL_num'] . "</td>
                <td>" . $row['WFL_item'] . "</td>
                <td>" . $row['WFL_desc'] . "</td>
                <td>$group</td>
                <td class='compHolder'><div style='width:$percentCompWidth;' class='wfCompDisplay'>&nbsp$percentText</div></td></tr>";
        }

        $output .= "</tbody></table></div></div>";
    } else {

        $output .= "No matching Workflows found.";
    }

} else {

    $output .= "No matching Workflows found.";
}

$output .= "</div></div>";

echo $output;

} else {

    echo "Post not set";
}

?>