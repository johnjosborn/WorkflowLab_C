<?php

//initiate the session (must be the first statement in the document)
session_start();

//linked files
require_once 'dbConnect.php';

//DEBUG
$custID = '1';


$conn = mysqli_connect($db_server, $db_username, $db_password, $db_database);

if (isset($_POST['item_id'])){

    $item = $_POST['item_id'];

    
    $sql = "SELECT DISTINCT ITM.ITM_id, ITM.ITM_num, ITM.ITM_desc, ITM.ITM_status, ITM_WFL_id
            FROM ITM
            WHERE ITM_CUS_id = '$custID' AND ITM.ITM_id = $item";

    $result = mysqli_query($conn,$sql);

    if($result){
        
        if($result->num_rows != 0){
            
            while($row = $result->fetch_assoc()){
                
                $itemID = $row['ITM_id'];
                $itemNum = $row['ITM_num'];
                $itemDesc = $row['ITM_desc'];
                $itemSta = $row['ITM_status'];
                $itemWf = $row['ITM_WFL_id'];
                
                $output = "<div class='contentTitle'>
                            Items Detail :  $itemNum
                        </div>
                        <div class='contentHolder'>";

                if($itemSta == 'Active'){
                        $staOptAc = 'selected';
                        $staOptIn = '';
                    } else {
                        $staOptAc = '';
                        $staOptIn = 'selected';                   
                    }

                    $templateSelect = "<select id='item_wf' class='textTableInput itemEditSelect' disabled>
                                            <option value='0'>None</option>";

                    //build wf template list
                    $sql = "SELECT DISTINCT WFL_id, WFL_num
                    FROM WFL
                    WHERE WFL_CUS_id = '$custID' AND WFL_status = 'Template'";

                    $result2 = mysqli_query($conn,$sql);

                    if($result2){
                        
                        if($result2->num_rows != 0){
                            
                            while($row2 = $result2->fetch_assoc()){

                                $wfID = $row2['WFL_id'];
                                $wfNum = $row2['WFL_num'];

                                if($itemWf == $wfID){
                                    $templateSelect .= "<option value='$wfID' selected>$wfNum</option>";
                                } else {
                                    $templateSelect .= "<option value='$wfID'>$wfNum</option>";
                                }

                            }

                            $templateSelect .= "</select>";
                        }
                    }
                    

                    $output .= "<table class='inputTable'>
                    <tr>
                        <th>
                            Item Number
                        </th>
                        <td>
                            <input type='text' id='item_num' class='textTableInput itemEdit' value='$itemNum' readonly>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Item Description
                        </th>
                        <td>
                            <input type='text' id='item_desc' class='textTableInput itemEdit' value='$itemDesc' readonly>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Item Status
                        </th>
                        <td>
                            <select id='item_sta' class='textTableInput itemEditSelect' disabled>
                                <option value='Active' $staOptAc>Active</option>
                                <option value='Inactive' $staOptIn>Inactive</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Default Workflow
                        </th>
                        <td>
                            $templateSelect
                        </td>
                    </tr>
                    <tr>
                    <th>
                    
                    </th>
                    <td>
                        <button class='button buttonBLue' onclick='editChangeItem()' id='btnEditItemChg'>Edit</button>
                        <button class='button buttonRed' onclick='saveChangeItem($itemID)' id='btnSaveItemChg' hidden>Save</button>
                        <button class='button buttonGray' onclick='getItemDetails($itemID)' id='btnUndoItemChg' hidden>Undo</button>
                    </td>
                </tr>
                </table>";
            }
        } else {

            $output .= "No items found.";
        }

    }

    $output .= "</div></div>";


} else {
    
    $output = "There was a problem retrieving items</div></div>";
}

echo $output;
?>