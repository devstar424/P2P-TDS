<?php 
include_once('include/config.php');

$successMessage = "Successfully Added Entry \n";
// Verify and Get Data
$postData = verifyApi($con);
$admin_name = $postData->adminData->admin_name;
$editId = $postData->editId;

$objHistoryNew = new stdClass();
$objHistoryNew->admin_name = $admin_name;
$objHistoryNew->pan = $postData->pan;
$objHistoryNew->orderId = $postData->orderId;
$objHistoryNew->name = $postData->name;
$objHistoryNew->mask_aadhar = $postData->mask_aadhar;
$objHistoryNew->link_aadhar = $postData->link_aadhar;
$objHistoryNew->amount = $postData->amount;
$objHistoryNew->phone = $postData->phone;
$objHistoryNew->postdate = $postData->postdate;

if($editId == 0){
    $objHistoryNew->postdate = $timestampCurrent;
    $historyId = sqlPostReq($con, $objHistoryNew, $strTableSqlHistory);
    echoJsonSuccess();
}else{
    $querySqlHistory = qryGet([], $strTableSqlHistory, "id=?");
    $paramsSqlHistory = [$editId];
    $objHistory = sqlGetReq($con, $querySqlHistory, $paramsSqlHistory);
    $objHistory->edit = json_decode($objHistory->edit);

    $objHistoryChange = objHistoryChange($objHistory, $objHistoryNew);
    $objHistoryChange->editBy = $admin_name;
    $objHistoryChange->editDate = $timestampCurrent;
    $objHistoryChange->editId = $editId;

    $arrHistory = [];
    if($objHistory->edit != null || $objHistory->edit != ""){
        $arrHistory = $objHistory->edit;
    }
    $arrHistory[] = $objHistoryChange;
    $objHistory->edit = json_encode($arrHistory);
    $updateHistory = sqlPutReq($con, $objHistory, $strTableSqlHistory, "id=?",[$editId]);
    if($updateHistory){
        echoJsonSuccess();
    }else{
        echoJsonFail("History Update Failed");
    }
}

function objHistoryChange($objHistory, $objHistoryNew){
    $objHistoryChange = new stdClass();
    $objHistoryChange->changes = new stdClass();
    $countHistoryChange = 0;
    foreach ($objHistoryNew as $key => $value) {
        if($objHistory->$key != $value){
            $objHistoryChange->changes->$key = valueObjChange($objHistory->$key, $value);
            $objHistory->$key = $value;
            $countHistoryChange++;
        }
    }
    $objHistoryChange->count = $countHistoryChange;
    return $objHistoryChange;
}

function valueObjChange($old,$new){
    return "$old --> $new";
}