<?php
date_default_timezone_set('Asia/Kolkata');
define('DB_SERVER','localhost');
define('DB_USER','root');
define('DB_PASS' ,'');
define('DB_NAME', 'sfpl_tds');
$con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
// Check connection
if (mysqli_connect_errno()){
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$timestampCurrent = date("Y-m-d H:i:s");

$strTableSqlAdmin = "admin";
$strTableSqlActiveAdmin = "active_admin";
$strTableSqlHistory = "history";

function getPostData(){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $body = file_get_contents("php://input");
        $postData = json_decode($body);
        if (json_last_error() === JSON_ERROR_NONE) {
           return $postData;
        }else{
            echoJsonFail("Invalid Post Json");
        }
    }else{
        echoJsonFail("Invalid Request");
    }
}
function verifyApi($con){
    $postData = getPostData();
    $qryActiveAdmin = qryGet(['id','admin_id','admin_name', 'expire'],"active_admin","token=?");
    $adminData = sqlGetReq($con,$qryActiveAdmin,[$postData->token]);
    if($adminData){
        $dateCurrent = new DateTime();
        $dateExpire = new DateTime($adminData->expire);
        if ($dateCurrent > $dateExpire) {
            $boolDeleteActiveAdmin = sqlDeleteReq($con,'active_admin',$adminData->admin_name,'id=?',[$adminData->id]);
            echoJsonInvalidToken();
        } else {
            $postData->adminData = $adminData;
            return $postData;
        }
    }else{
        echoJsonInvalidToken();
    }
}


function echoJsonInvalidToken(){
    $response = new stdClass(); 
    $response->res = "ft"; 
    $response->message = "Please Login First..."; 
    header('Content-Type: application/json');
    echo json_encode($response); 
    exit();
}
function echoJsonFail($message) {
    $response = new stdClass(); 
    $response->res = "f"; 
    $response->message = $message; 
    header('Content-Type: application/json');
    echo json_encode($response); 
    exit();
}

function echoJsonSuccess($data = new stdClass,$message = "Success") {
    $response = new stdClass(); 
    $response->res = "s"; 
    $response->message = $message;
    $response->data = $data;
    header('Content-Type: application/json');
    echo json_encode($response);
}


function qryGet($columns,$table, ...$conditions){
    $qry = "SELECT";
    if(count($columns) == 0){
        $qry .= " *";
    }else{
        foreach($columns as $column){
            $qry .= " $column, ";
        }
        $qry = rtrim($qry,", ");
    }
    $qry .= " FROM `$table`";
    if (!empty($conditions)) { 
        $qry .= " WHERE"; 
        foreach ($conditions as $condition) { 
            $qry .= " $condition "; 
        }
    }
    return $qry;
}
function sqlGetReq($con,$query,$params = [],$offset = 0,$limit = 1,$haveLimit = true){
    if($haveLimit){
        $query .= " LIMIT ?, ?";
        $params[] = $offset;
        $params[] = $limit;
    }
    $types = str_repeat("s", count($params));
    $sql = mysqli_prepare($con, $query);
    if(!empty($types)){
        mysqli_stmt_bind_param($sql, $types, ...$params);
    }
    mysqli_stmt_execute($sql); 
    $result = mysqli_stmt_get_result($sql); 
    if($limit == 1 && $haveLimit){
        return mysqli_fetch_object($result);
    }else{
        $rows = [];
        while ($row = mysqli_fetch_object($result)) { 
            $rows[] = $row; 
        }
        return $rows;
    }
}

function sqlPostReq($con,$row,$table){
    $tempStr = "(";
    $types = "";
    $params = [];
    $qry = "INSERT INTO `$table` (";
    foreach($row as $key => $value){
        $qry.= " `$key`, ";
        $tempStr.= "?, ";
        $types .= "s";
        $params[] = $value;
    }
    $qry = rtrim($qry,", ");
    $tempStr = rtrim($tempStr,", ")." )";
    $qry.= (") VALUES ".$tempStr);

    $sql = mysqli_prepare($con, $qry);
    $bind = mysqli_stmt_bind_param($sql, $types, ...$params);
    $exec = mysqli_stmt_execute($sql);
    if (!$sql || !$bind || !$exec) {
        echoJsonFail("Unable to Insert");
        return false; 
    }
    return mysqli_insert_id($con);
}

function sqlPutReq($con,$row,$table,$condition,$conditionParams){
    $types = "";
    $params = [];
    $qry = "UPDATE `$table` SET ";
    foreach($row as $key => $value){
        if (is_object($value) || is_array($value)) { 
            $value = json_encode($value);
        }
        $qry.= " `$key`=?, ";
        $types.= "s";
        $params[] = $value;
    }
    $qry = rtrim($qry,", ")." WHERE $condition";
    foreach($conditionParams as $conditionParam){
        $params[] = $conditionParam;
        $types .= "s";
    }

    $sql = mysqli_prepare($con, $qry);
    $bind = mysqli_stmt_bind_param($sql, $types,...$params);
    $exec = mysqli_stmt_execute($sql);

    if (!$sql || !$bind || !$exec) {
        echoJsonFail("Unable to Update");
        return false; 
    }

    return true;
}

function sqlDeleteReq($con,$table,$adminName,$condition,$conditionParams){
    $arrNoHistoryTable = ['active_admin'];
    $types = "";
    $params = [];
    foreach($conditionParams as $conditionParam){
        $params[] = $conditionParam;
        $types.= "s";
    }
    if(!in_array($table,$arrNoHistoryTable)){
        $qry = qryGet([],$table,$condition);
        $row = sqlGetReq($con,$qry,$params);
        ['dlt_by', 'dlt_table', 'dlt_row', 'dlt_postdate'];
        $objDeletedRow = new stdClass();
        $objDeletedRow->dlt_by = $adminName;
        $objDeletedRow->dlt_table = $table;
        $objDeletedRow->dlt_row = json_encode($row);
        $objDeletedRow->dlt_postdate = date("Y-m-d H:i:s");
        $insertId = sqlPostReq($con,$objDeletedRow,"deleted_row");
    }

    $qry = "DELETE FROM `$table` WHERE $condition";
    $sql = mysqli_prepare($con, $qry);
    $bind = mysqli_stmt_bind_param($sql, $types,...$params);
    $exec = mysqli_stmt_execute($sql);

    if (!$sql || !$bind || !$exec) {
        echoJsonFail("Unable to Delete");
        return false; 
    }
    return true;
}