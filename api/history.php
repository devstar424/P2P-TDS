<?php 
include_once('include/config.php');

// Verify and Get Data
$postData = verifyApi($con);
$sendData = new stdClass();

// Create Query for Sql Server
$querySqlPrd = qryGet([],$strTableSqlHistory);

// Connect to database and Get Data
$rowsSqlPrd = sqlGetReq($con,$querySqlPrd,offset: $postData->offset,limit: $postData->limit);
$sendData->products = $rowsSqlPrd;
$sendData->hasMore = (count($rowsSqlPrd) == $postData->limit);
// Send Response
echoJsonSuccess($sendData);