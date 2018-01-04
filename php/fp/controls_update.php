<?php

//DEBUG
$custID = '1';

//linked files
require_once 'dbConnect.php';
//require_once 'fp/logInValidation.php'; //$userID, $custID

//get list of items for select boxes
$itemSelect = "<div class='inputControl'><select class='inputField controlSelect' id='wfByItem'><option disabled selected>Select Item</option>";

$sql = "SELECT DISTINCT WFL_item
        FROM WFL
        WHERE WFL_CUS_id = '$custID' AND WFL_item NOT LIKE '0'
        ORDER BY WFL_item";

$result = mysqli_query($conn,$sql);

if($result){
    
    if($result->num_rows != 0){

        while($row = $result->fetch_assoc()){

            $item = $row['WFL_item'];

            $itemSelect .= "<option value ='$item'>$item</option>";
        }

    }
}

$itemSelect .= "</select></div>";

//get list of items for select box
$groupSelect = "<div class='inputControl'><select class='inputField controlSelect' id='wfByGroup'><option disabled selected>Select Group</option>";

$sql = "SELECT DISTINCT WFL_group
        FROM WFL
        WHERE WFL_CUS_id = '$custID' AND WFL_group NOT LIKE '0'
        ORDER BY WFL_group";

$result = mysqli_query($conn,$sql);

if($result){
    
    if($result->num_rows != 0){

        while($row = $result->fetch_assoc()){

            $group = $row['WFL_group'];

            $groupSelect .= "<option value ='$group'>$group</option>";
        }

    }
}

$groupSelect .= "</select></div>";

$ctrlWf = "
    <div onclick='newWorkflow()' class='selectRadioNew'>+ New Workflow</div>
    <hr>
    <div class='listLabel'>WORKFLOWS BY STATUS</div>
    <div id='radio-wf-active' onclick='getWorkflowList(\"Active\")' class='selectRadio'>Active</div>
    <div id='radio-wf-comp' onclick='getWorkflowList(\"Complete\")' class='selectRadio'>Completed</div>
    <div id='radio-wf-pend' onclick='getWorkflowList(\"Pending\")' class='selectRadio'>Pending</div>
    <div id='radio-wf-all' onclick='getWorkflowList(\"%\")' class='selectRadio'>All</div>
    <hr>
    <div id='radio-wf-temp' onclick='getWorkflowList(\"Template\")' class='selectRadio'>Templates</div>
    <hr>
    <div id='radio-wf-item' class='selectRadio'>View by Item</div>
        $itemSelect
    <div id='radio-wf-group' class='selectRadio'>View by Group</div>
        $groupSelect
    <hr>
    <div id='radio-wf-text' class='selectRadio'>Text Search</div>
    <div class='inputControl'>
        <input type='text' id='stringSearchWF' class='inputField2'>
        <input type='button' onclick='getWorkflowListString()' class='button buttonBlue inlineB' value='Search'>
        <input type='hidden' id='wfSelection' value='Active'>
    </div>";

    //get list of items for select boxes
$stepTypes = "<div class='inputControl'><select class='inputField controlSelect' id='opByType'><option disabled selected>Select Type</option>";

$sql = "SELECT DISTINCT OPS_type
        FROM OPS
        WHERE OPS_CUS_id = '$custID'
        ORDER BY OPS_type";

$result = mysqli_query($conn,$sql);

if($result){
    
    if($result->num_rows != 0){

        while($row = $result->fetch_assoc()){

            $op = $row['OPS_type'];

            $stepTypes .= "<option value ='$op'>$op</option>";
        }

    }
}

$stepTypes .= "</select></div>";

$ctrlStep = "
    <div onclick='newOp()' class='selectRadioNew'>+ Add New Step</div>
    <hr>
    <div class='listLabel'>STEPS BY STATUS</div>
    <div id='radio-op-active' onclick='getOpList(\"Active\")' class='selectRadio'>Active</div>
    <div onclick='getOpList(\"Inactive\")' class='selectRadio'>Inactive</div>
    <div onclick='getOpList(\"%\")' class='selectRadio'>All</div>
    <hr>
    <div id='radio-op-item' class='selectRadio'>View by Type</div>
        $stepTypes
    <hr>
    <div id='radio-op-text' class='selectRadio'>Text Search</div>
    <div class='inputControl'>
        <input type='text' id='stringSearchOp' class='inputField2'>
        <input type='button' onclick='getOpListString()' class='button buttonBlue inlineB' value='Search'>
        <input type='hidden' id='stepSelection' value='Active'>
    </div>";

$controls = "
    <div class='accd_header accd_header_selected' onclick='h0()'>
        Home
    </div>
    <div id='activeControl'>
        
    </div>
    <div class='accd_header' onclick='h1()'>
        Workflows
    </div>
    <div class='accd_content' id='c1' hidden>
        $ctrlWf
    </div>
    <div class='accd_header'  onclick='h3()'>
        Steps
    </div>
    <div class='accd_content' id='c3' hidden>
        $ctrlStep
    </div>
    <div class='accd_header'  onclick='h4()'>
        Users
    </div>
    <div class='accd_content' id='c4' hidden>
        Content 4
    </div>
    <input type='hidden' id='currentSelection' value=''>";

echo $controls;



?>