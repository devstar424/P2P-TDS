<?php 
include_once('include/config.php');

$postData = getPostData();
$sendData = new stdClass();
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$ip = $_SERVER['REMOTE_ADDR'];
$token = bin2hex(random_bytes(16));
$arrColumnActiveAdmin = ['admin_id', 'admin_name', 'token', 'expire', 'useragent', 'ip', 'postdate'];

$adminData = getAdmin($con,$postData->username,$postData->password);

$objActiveAdmin = new stdClass();
$objActiveAdmin->admin_id = $adminData->id;
$objActiveAdmin->admin_name = $adminData->name;
$objActiveAdmin->token = $token;
$objActiveAdmin->expire = date('Y-m-d H:i:s', strtotime('+365 days'));
$objActiveAdmin->useragent = $userAgent;
$objActiveAdmin->ip = $ip;
$objActiveAdmin->postdate = $timestampCurrent;

$activeAdminId = sqlPostReq($con, $objActiveAdmin, $strTableSqlActiveAdmin);

$sendData->token = $token;
$sendData->adminName = $adminData->name;
$sendData->surepasstoken = "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJmcmVzaCI6ZmFsc2UsImlhdCI6MTczODU2Nzc1NiwianRpIjoiZTQwMDZhMjUtOTBjOC00MzM1LTkzMzEtOWMyMTg5ODVkYTllIiwidHlwZSI6ImFjY2VzcyIsImlkZW50aXR5IjoiZGV2LnNodXJhdkBzdXJlcGFzcy5pbyIsIm5iZiI6MTczODU2Nzc1NiwiZXhwIjoyMzY5Mjg3NzU2LCJlbWFpbCI6InNodXJhdkBzdXJlcGFzcy5pbyIsInRlbmFudF9pZCI6Im1haW4iLCJ1c2VyX2NsYWltcyI6eyJzY29wZXMiOlsidXNlciJdfX0.b2JBKNxQ_Y31SQn0Padj1S5OvrqaT1xXwIAF-RdnlI0";
echoJsonSuccess($sendData);


function getAdmin($con,$username,$password){
    $username = md5((strtolower($username)));
    $password = md5(($password));
    $qryAdmin = qryGet(['id','name'],"admin","username=? and password=?");
    $row = sqlGetReq($con,$qryAdmin,[$username,$password]);
    if($row){
        return $row;
    }else{
        echoJsonFail("Invalid Id or Password!");
    }
}

