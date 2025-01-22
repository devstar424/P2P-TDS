<?php 
include_once('include/config.php');

// Verify and Get Data
$postData = verifyApi($con);
$boolDeleteActiveAdmin = sqlDeleteReq($con,$strTableSqlHistory,$postData->adminData->admin_name,'id=?',[$postData->id]);

if ($boolDeleteActiveAdmin){
    echoJsonSuccess();
}else{
    echoJsonFail("Failed to Delete Active Account");
}