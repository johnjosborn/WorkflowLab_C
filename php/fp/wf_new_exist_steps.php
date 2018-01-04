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

if (isset($_POST['wf_id'])){
    
    $wfID = $_POST['wf_id'];

    //All the steps
    $sql = "SELECT STP_id, STP_title, STP_desc, STP_order, STP_status, STP_note, STP_detail, STP_date, USR_name
            FROM STP
            INNER JOIN USR ON STP.STP_USR_id = USR.USR_id
            WHERE STP_WFL_id = '$wfID'
            ORDER By STP_order";
            
    $result = mysqli_query($conn,$sql);

    if($result){

        $steps = "<div id='accordionHolder' class=''>
                    <div id='accordianScroll' class='container scrollable'>
                    <div id='stepAccordian' class=''>";

        if($result->num_rows != 0){

            while($row = $result->fetch_assoc()){

                $thisStatus = $row['STP_status'];

                $markOpen = "";

                switch($thisStatus){

                    case "Open":
                        $class = "stepOpen";
                        $markOpen = "id='openStep'";
                    break;

                    case "Pending":
                        $class = "stepPending";
                    break;

                    case "Complete":
                        $class = "stepComplete";
                    break;
                }

                $stpTitle = $row['STP_title'];
                $stpDesc = $row['STP_desc'];
                $stpDetails = $row['STP_detail'];
                $stpOrder = $row['STP_order'];
                $stpDate = $row['STP_date'];
                $stpNotes = $row['STP_note'];
                $stpUsr = $row['USR_name'];

                $steps .= "<div class='stepHeader $class' $markOpen>
                    <div class='orderBox'>$stpOrder</div>
                    <div class='titleBox'>$stpTitle</div>
                    <div class='titleBox'>$stpDesc</div>
                </div>
                <div class='stepDetail'>
                    <div class='detLabel'>Details</div>
                    <div class='detData minHeight'>$stpDetails</div>
                    <div class='detLabel'>Notes</div>
                    <div class='detData minHeight'>$stpNotes</div>
                    <div class='detLabel'>Completed By:</div>
                    <div class='detData'>$stpUsr on  $stpDate</div>
                    </div>";
            }

        }

        $steps .= "</div></div>";

    } else {  $output = "Bad query steps"; }

} else {
    
    $output = "Post not set.";
}

$output = "<div class='contentTitle' id='activeTitle'>
                <div>New Workflow</div>
            </div>
            </div>
            <div class='contentHolder'>
                <div id='wfHeader'>
                    Steps to be Copied (you can add / edit / remove them after creating this new workflow)
                </div>
                $steps
            </div>";

echo $output;
        
?>    