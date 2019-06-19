<?php

require 'dbconnect.php';
date_default_timezone_set('Asia/Kolkata');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tax_id = trim(filter_var($_POST['TAX_ID'], FILTER_SANITIZE_STRING));
    $name = trim(filter_var($_POST['PARTY_NAME'], FILTER_SANITIZE_STRING));
    $pan_no = trim(filter_var($_POST['PAN_NO'], FILTER_SANITIZE_STRING));
    $add1 = trim(filter_var($_POST['ADDRESS1'], FILTER_SANITIZE_STRING));
    $add2 = trim(filter_var($_POST['ADDRESS2'], FILTER_SANITIZE_STRING));
    $add3 = trim(filter_var($_POST['ADDRESS3'], FILTER_SANITIZE_STRING));
    $pin_no = trim(filter_var($_POST['PIN_NO'], FILTER_SANITIZE_STRING));
    $mobile_no = trim(filter_var($_POST['MOBILE_NO'], FILTER_SANITIZE_STRING));
    $remarks = trim(filter_var($_POST['REMARKS'], FILTER_SANITIZE_STRING));
    $amount1 = trim(filter_var($_POST['AMOUNT1'], FILTER_SANITIZE_STRING));
    $challan_amount = trim(filter_var($_POST['CHALLAN_AMOUNT'], FILTER_SANITIZE_STRING));
    $appointment_id = trim(filter_var($_POST['appointment_id'], FILTER_SANITIZE_STRING));

    // generate a unique departmentid
    $dept_id = "Ele";

    try {
        $stmt = $pdo->query('SELECT max(ID) from payment');
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['max(ID)'] == NULL) {
            $dept_id = $dept_id . 5000;
        }
        else {
            $dept_id .= ($row['max(ID)'] + 1);
        }
   
   
        // contains all the parameters to post on e-GRAS
        $arr = array();

        $arr['DEPT_CODE'] = 'LRS';
        $arr['PAYMENT_TYPE'] = '01';
        $arr['OFFICE_CODE'] = 'LRS000';
        $arr['REC_FIN_YEAR'] = date('Y') . "-" . (date('Y') + 1);
        $arr['PERIOD'] = 'O';
        $arr['FROM_DATE'] = "01/04/" . date('Y');
        $arr['TO_DATE'] =  "31/03/2099";
        $arr['DEPARTMENT_ID'] = $dept_id;
        $arr['SUB_SYSTEM'] = "GRAS-APP";
        $arr["TREASURY_CODE"] = "BIL";
        $arr["MAJOR_HEAD"] = "0029";
        $arr['HOA1'] = "0029-00-101-0000-000-01";
        $arr['AMOUNT1'] = $amount1;
        $arr['REMARKS'] = $remarks;
        $arr['CHALLAN_AMOUNT'] = $challan_amount;
        $arr['TAX_ID'] = $tax_id;
        $arr['PAN_NO'] = $pan_no;
        $arr['PARTY_NAME'] = $name;
        $arr['ADDRESS1'] = $add1;
        $arr['ADDRESS2'] = $add2;
        $arr['ADDRESS3'] = $add3;
        $arr['PIN_NO'] = $pin_no;
        $arr['MOBILE_NO'] = $mobile_no;

        //  Write data to payment
        $sql = "INSERT INTO payment(DEPARTMENTID, OFFICE_CODE, REQUESTPARAMETERS, AMOUNT, CHALLAN_DATE) VALUES (?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $dept_id, PDO::PARAM_STR);
        $stmt->bindValue(2, $arr['OFFICE_CODE'], PDO::PARAM_STR);
        $stmt->bindValue(3, json_encode($arr), PDO::PARAM_STR);
        $stmt->bindValue(4, $arr['CHALLAN_AMOUNT'], PDO::PARAM_INT);
        $stmt->bindValue(5, date('Y-m-d'), PDO::PARAM_STR);
        $stmt->execute();


        // update appointment_slot_booking 
        $sql = "UPDATE appointment_slot_booking SET DEPARTMENTID = ? WHERE appointment_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $dept_id, PDO::PARAM_STR);
        $stmt->bindValue(2, $appointment_id, PDO::PARAM_STR);
        $stmt->execute();


        // convert '$arr' to a string of the form 'key=value&key=value'
        $postData = '';
        foreach ($arr as $key => $value) {
            $postData .= $key . "=" . $value . "&";
        }
        // remove the trailing "&" 
        $postData = trim(substr($postData, 0, -1));
        
        // Send data for android to POST on eGRAS site
        $data = array();
        $data['success'] = true;
        $data['data'] = $postData;
        $data['url'] = "http://103.8.248.139/challan/views/frmgrnfordept.php";
    } 
    catch (Exception $e) {
        $data['success'] = false;
        $data['data'] = NULL;
    }


    // sending response back to Android
    echo json_encode($data);
}