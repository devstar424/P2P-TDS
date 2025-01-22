<?php 
include_once('include/config.php');

// Verify and Get Data
$postData = verifyApi($con);
$boolDeleteActiveAdmin = sqlDeleteReq($con,$strTableSqlActiveAdmin,"logout",'token=?',[$postData->token]);

if ($boolDeleteActiveAdmin){
    echoJsonSuccess();
}else{
    echoJsonFail("Failed to Delete Active Account");
}