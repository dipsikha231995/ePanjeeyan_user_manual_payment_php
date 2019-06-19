<?php
require '../../vendor/autoload.php';

use GuzzleHttp\Client;

// {"DEPARTMENT_ID":"Ele2120","GRN":"AS000000990201920E","OFFICE_CODE":"LRS000","AMOUNT":"19","ACTION_CODE":"Y"}

$client = new Client(['base_uri' => 'http://103.8.248.139']);


/*
//Print Challan
$response = $client->request('POST', '/challan/views/frmEpayEchallanPrintMerge.php', 
[
    'form_params' => [
        'DEPARTMENT_ID' => 'Ele2120',
        'GRN' => 'AS000000990201920E',
        'OFFICE_CODE' => 'LRS000',
        'AMOUNT' => 19,
        'ACTION_CODE' => 'Y'
    ]
]);

// GETGRN
$response = $client->request('POST', '/challan/models/frmgetgrn.php', 
[
    'form_params' => [
        'DEPARTMENT_ID' => 'Ele2120',
        'OFFICE_CODE' => 'LRS000',
        'AMOUNT' => 19
    ]
]);
*/


// PHP cURL POST: GETGRN
/*$ch = curl_init();                  // init a cURL session 

$data = array(
    'DEPARTMENT_ID' => 'Ele2120',
    'OFFICE_CODE' => 'LRS000',
    'AMOUNT' => 19
);

// set cURL options
curl_setopt_array($ch,
    array(
        CURLOPT_URL => "http://103.8.248.139/challan/models/frmgetgrn.php",
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_RETURNTRANSFER => true
    )
);

$output = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);                 // close the cURL session 

echo $output . "<br>";

print_r($info);

*/


//GETCIN
// OFFICE_CODE=LRS000&DEPARTMENT_ID=Ele2140&AMOUNT=9&ACTION_CODE=GETCIN&SUB_SYSTEM=GRAS-APP
$response = $client->request('POST', '/challan/models/frmgetgrn.php', 
[
    'form_params' => [
        'DEPARTMENT_ID' => 'Ele2145',
        'OFFICE_CODE' => 'LRS000',
        'AMOUNT' => 16,
        'ACTION_CODE' => 'GETCIN',
        'SUB_SYSTEM' => 'GRAS-APP'
    ]
]);




if ($response->getStatusCode() == 200) {
    $data = $response->getBody();

    echo $data;
}