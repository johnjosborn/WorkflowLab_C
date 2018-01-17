<?php

//initiate the session (must be the first statement in the document)
session_start();

//linked files
require_once 'dbConnect.php';
//require_once 'logInValidation.php'; //$userID, $custID

//DEBUG
$custID = '1';

$output = "initial";

$usP0 = 'Selected';
$usP10 = '';
$usP20 = '';
$usP30 = '';

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['us_id'])){

    $output = "posted";
    
    $usID = $_POST['us_id'];

    //Query Workflow

    $sql = "SELECT USR_id, USR_name, USR_perm, USR_accessDate, USR_email, USR_phone
            FROM USR
            WHERE USR_CUS_id = '$custID' AND USR_id = '$usID'";
        
    $result = mysqli_query($conn,$sql);

    if($result){

        
        if($result->num_rows != 0){
            
            while($row = $result->fetch_assoc()){

                $usID = $row['USR_id'];
                $usName = $row['USR_name'];
                $usType = $row['USR_perm'];
                $usLA = $row['USR_accessDate'];
                $usMail = $row['USR_email'];
                $usPhone = $row['USR_phone'];
            }
       

            switch ($usType){
                case "0":
                $usP0 = "selected ='selected'";
                break;
                case "10":
                $usP10 = "selected ='selected'";
                break;
                case "20":
                $usP20 = "selected ='selected'";
                break;
                case "30":
                $usP30 = "selected ='selected'";
                break;

            }

            $statusSelect = "<select id='usPerm' class='textTableInput' onchange='usPermChange(this)'>
                            <option value='0' $usP0>Inactive</option>
                            <option value='10' $usP10>Standard User</option>
                            <option value='20' $usP20>Power User</option>  
                            <option value='30' $usP30>Administrator</option>                  
                        </select>
                        <input type='hidden' id='usPermStore' value='$usType'>";

            $output = "
            <div id='activeItem' class='accd_header_active' onclick='hActive()'>
                    User Details: $usName
                </div>
                <div id='activeContent' class='accd_content' hidden>
                    <div id='usContainer' class='container'>
                        <div class='labelDiv'>Name</div>
                        <div class='dataInputDiv'>
                            <input id='usName' value='$usName' class='textTableInput'></div>
                        <div class='labelDiv'>email</div>
                        <div class='dataInputDiv'>
                            <input id='usMail' value='$usMail' class='textTableInput'></div>
                        <div class='labelDiv'>phone</div>
                        <div class='dataInputDiv'>
                            <input id='usPhone' value='$usPhone' class='textTableInput'></div>
                        <div class='labelDiv'>Access Level</div>
                        <div class='dataInputDiv'>$statusSelect</div>                        
                        <div class='scaleText1 alignRight'>
                            <input type='button' class='button buttonGreen editH1' onclick='saveUsDetail($usID)' value='Save' hidden>
                            <input type='button' class='button buttonGray editH1' onclick='editUs($usID)' value='Undo' hidden>
                            <input type='button' class='button buttonRed editH2' onclick='deleteUs($usID)' value='DELETE'>
                        </div>
                        <input type='hidden' id='usID' value='$usID'>
                    </div>
                </div>";


        } else { $output = "User not found.";}

    } else { $output = "User not found.";}

} else { $output = "Data not sent."; }

echo $output;
?>