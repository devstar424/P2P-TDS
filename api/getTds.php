<?php 
include_once('include/config.php');

// Verify and Get Data
$postData = verifyApi($con);
$sendData = new stdClass();

// Create Query for Sql Server
$querySqlKhata = qryGet([],$strTableSqlHistory,"id = ?");
$paramsSqlKhata = [$postData->id];


// Connect to database and Get Data
$sendData = sqlGetReq($con,$querySqlKhata,$paramsSqlKhata);
// Send Response
echoJsonSuccess($sendData);