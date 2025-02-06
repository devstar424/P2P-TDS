<?php 
include_once('include/config.php');

// Verify and Get Data
$postData = verifyApi($con);
$dateStart = $postData->dateStart;
$dateEnd = $postData->dateEnd;
$sendData = new stdClass();
$dataCsv = "pan,name,amount,date,tds,paid\n";
// Create Query for Sql Server
$querySqlKhata = qryGet(['pan', 'name', 'amount', 'postdate', 'tds', 'paid'],$strTableSqlHistory,"DATE(postdate) >= ? AND DATE(postdate) <= ?");
$paramsSqlKhata = [$postData->dateStart,$postData->dateEnd];


// Connect to database and Get Data
$arr = sqlGetReq($con,$querySqlKhata,$paramsSqlKhata,haveLimit: false,reverse: false);
foreach($arr as $row){
    $dataCsv.= $row->pan.",".$row->name.",".$row->amount.",".$row->postdate.",".$row->tds.",".$row->paid."\n";
}
$dataCsv .= $dateStart.", to ,".$dateEnd.",,,\n";
$sendData->csv = $dataCsv;
echoJsonSuccess($sendData);

// $file = 'exp.csv';
// $result = file_put_contents($file, $dataCsv);
// // Send Response
// if($result){
//     echoJsonSuccess();
// }