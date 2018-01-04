<?php

//initiate the session (must be the first statement in the document)
session_start();

//linked files
require_once 'dbConnect.php';
//require_once 'logInValidation.php'; //$userID, $custID

//DEBUG
$custID = '1';

//default declaration values
$foundOpen = false;
$workflowProgress = 0;
$compSteps = 0;
$totalSteps = 0;

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

        $steps = "";

        if($result->num_rows != 0){

            while($row = $result->fetch_assoc()){

                $thisStatus = $row['STP_status'];

                $totalSteps++;

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
                        $compSteps++;
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
                                <div class='detData minHeight'>$stpDetails</div>";

                if ($thisStatus == "Complete"){

                    $steps .= " <div class='detLabel'>Notes</div>
                                <div class='detData minHeight'>$stpNotes</div>
                                <div class='detLabel'>Completed By:</div>
                                <div class='detData'>$stpUsr on  $stpDate</div>
                                ";

                } else if ($thisStatus == "Open"){
                    $steps .= " <div class='detLabel'>Notes</div>
                                <div class='detData'>
                                    <textarea id='" . $row['STP_id'] . "note' rows='3'>$stpNotes</textarea>
                                </div>
                                <div>
                                <input type='button' onclick='completeStep(\"" . $row['STP_id'] . "\")' value='Complete Step' class='button buttonGreen'/>
                                </div>";
                }

                    $steps .= "</div>";

            }

        }

        $steps .= "";

    } else {
        $output = "Bad query steps"; 
    }
    
        $sql = "SELECT WFL_item, WFL_num, WFL_desc, WFL_status, WFL_notes, WFL_ref, WFL_group
        FROM WFL
        WHERE WFL_CUS_id = '$custID' AND WFL_id = '$wfID'";

        $result = mysqli_query($conn,$sql);

        if($result){

            if($result->num_rows != 0){

                while($row = $result->fetch_assoc()){

                    $wfNum = $row['WFL_num'];
                    $wfItem = $row['WFL_item'];
                }
            
            }
        }

} else {
    
    $output = "Post not set.";
}



$output = " <div class='contentTitle' id='activeTitle'>
                <div>Workflow#&nbsp$wfNum&nbsp&nbspItem:&nbsp$wfItem</div>
            </div>
            <div class='contentHolder'>
                <div id='wfHeader'>
                    <div id='progress'>
                        <div class='progressText' class='inlineB'>
                            $compSteps of $totalSteps steps complete.
                        </div>
                    </div>
                </div>
                <hr>
                <div id='accordianScroll' class='container scrollable'>
                <div id='stepAccordian' class=''>
                    $steps
                </div>
            </div>";

echo $output;


    

?>