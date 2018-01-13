<?php

//initiate the session (must be the first statement in the document)
session_start();

//linked files
require_once 'dbConnect.php';

//DEBUG
$custID = '1';

if (isset($_POST['user_type'])){

    $searchType = $_POST['user_type'];

    switch ($searchType){

        case "X":

            //all active users

            $whereClause = "AND USR_perm > 0";

            $title = 'Active Users';

            break;

        case "30":

            $whereClause = "AND USR_perm = 30";

            $title = 'Administrators';

            break;

        case "20":

            $whereClause = "AND USR_perm = 20";

            $title = 'Power Users';

            break;

        case "10":

            $whereClause = "AND USR_perm = 10";

            $title = 'Standard Users';

            break;
            
        case "0":

            $whereClause = "AND USR_perm = 0";

            $title = 'Inactive Users';

            break;

        case "A":

            $whereClause = "";

            $title = 'All Users';

            break;

        default:

            $whereClause = "";

            $title = 'Default User List';

            break;

    }

    $output = "<div class='contentTitle'>
                    $title
                </div>
                <div class='contentHolder'>";

    $sql = "SELECT USR_id, USR_name, USR_perm, USR_accessDate
            FROM USR
            WHERE USR_CUS_id = '$custID' $whereClause";
        
    $result = mysqli_query($conn,$sql);

    if($result){

    if($result->num_rows != 0){

        $output .= "<table id='usList' class='listTable tablesorter'>
        <thead>
            <tr>
                <th>User Name</th>
                <th>Type</th>
                <th>Last Access</th>
            </tr>
        </thead>
        <tbody>";

        while($row = $result->fetch_assoc()){

            $usID = $row['USR_id'];
            $usName = $row['USR_name'];
            $usType = $row['USR_perm'];
            $usLA = $row['USR_accessDate'];

            switch ($usType){

                case "0":
                
                    $uPermText = 'Inactive User';
        
                    break;
        
                case "10":

                    $uPermText = 'Standard User';
            
                    break;

                case "20":

                    $uPermText = 'Power User';
            
                    break;

                case "30":

                    $uPermText = 'Administrator';
            
                    break;

            }
            

            $output .= "<tr onclick='editUs($usID)'>
                <td>$usName</td>
                <td>$uPermText</td>
                <td>$usLA</td>
                </tr>";
        }

        $output .= "</tbody></table></div></div>";
    } else {

        $output .= "No matching Users found.";
    }

} else {

    $output .= "No matching Users found.";
}

$output .= "</div></div>";

echo $output;
// echo $sql;

} else {

    echo "Post not set";
}

?>