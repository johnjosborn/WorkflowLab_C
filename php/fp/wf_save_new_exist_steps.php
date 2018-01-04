<?php

//initiate the session (must be the first statement in the document)
session_start();

//linked files
require_once 'dbConnect.php';
//require_once 'logInValidation.php'; //$userID, $custID

//DEBUG
$custID = '1';

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['wkf_ID'])){

    $wfID = $_POST['wkf_ID'];
    $exID = $_POST['ex_ID'];

    //All the steps
    $sql = "SELECT STP_id, STP_title, STP_desc, STP_order, STP_status, STP_note, STP_detail, STP_date, STP_USR_id
            FROM STP
            WHERE STP_WFL_id = '$exID'
            ORDER By STP_order";
            
    $result = mysqli_query($conn,$sql);

    if($result){

        if($result->num_rows != 0){

            //prepare insert into new workflow
            $stmt = $conn->prepare("INSERT INTO STP (STP_WFL_id, STP_title, STP_desc, STP_order, STP_status, STP_note, STP_detail, STP_date, STP_USR_id)
            VALUES (?,?,?,?,?,?,?,?,?)");

            while($row = $result->fetch_assoc()){

                $title = $row['STP_title'];
                $desc = $row['STP_desc'];
                $order = $row['STP_order'];
                $status = $row['STP_status'];
                $note = $row['STP_note'];
                $detail = $row['STP_detail'];
                $date = $row['STP_date'];
                $user  = $row['STP_USR_id'];

                $stmt->bind_param("ississssi", $wfID, $title, $desc, $order, $status, $note, $detail, $date, $user);

                $stmt->execute();

            }
        }
    }
}


?>