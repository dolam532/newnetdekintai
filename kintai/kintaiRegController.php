<?php

session_start();

// Include const.php
include '../inc/const.php';
include '../inc/query.php';
// Include kintaoDAO -> Data access Object 
include('../inc/message.php');
include('./container.php');
include('dao/KintaiRegDAOImpl.php');

// use container.php
$customContainer = new Container();
$kintaiRegDAO = $customContainer->get(KintaiRegRepository::class);

// Get data from db with id = id , year = CbbYear , Month = cbbMonth 
$year = isset($_GET['year']) ? htmlspecialchars($_GET['year']) : null;
$month = isset($_GET['month']) ? htmlspecialchars($_GET['month']) : null;
$type_get = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : null;

$checkFlagAddedNewData = false;

// $_SESSION['auth'] == false;
$uidCurrent = $_SESSION['auth_uid'];
// call user model get genid 

// GET request
if (isset($type_get) && in_array($type_get, [$TYPE_GET_WORK_YEAR_MONTH_DAY, 'otherType'])) { // get data for work year month day
        // get data work year month 
        $data = isset($_GET['data']) ? htmlspecialchars_decode($_GET['data']) : null;
        $result = $kintaiRegDAO->getWorkOfMonth($data, $uidCurrent);
        returnValueTemplate($result);
} else if (isset($type_get) && in_array($type_get, [$TYPE_GET_WORK_YEAR_MONTH, 'otherType'])) { // get data for year month -> TABLE_WORK_MONTH
        //  get total of month => kyukaMonthly.php ???
        $data = isset($_GET['data']) ? htmlspecialchars_decode($_GET['data']) : null;
        $result = $kintaiRegDAO->getTotalWorkMonth($data, $uidCurrent);
        returnValueTemplate($result);

} else if (isset($type_get) && in_array($type_get, [$TYPE_INSERT_MISSING_WORK_YEAR_MONTH_DAY, 'otherType'])) {
        // insert data of month if data missing
        $data = isset($_GET['data']) ? htmlspecialchars_decode($_GET['data']) : null;
        $result = $kintaiRegDAO->insertMany($data, $uidCurrent);
        $result === 1 ? returnValueTemplate($result) : returnValueTemplate($ADD_DATA_ERROR);

} else if (isset($type_get) && in_array($type_get, [$TYPE_INSERT_NEW_WORK_YEAR_MONTH_DAY, 'otherType'])) {
        // add current month to db if current month data is not exists in db 
        $data = isset($_GET['data']) ? htmlspecialchars_decode($_GET['data']) : null;
        $result = $kintaiRegDAO->insertNewMonth($data, $uidCurrent);
        $result === 1 ? returnValueTemplate($result) : returnValueTemplate($ADD_DATA_ERROR);

} else if (isset($type_get) && in_array($type_get, [$TYPE_REGISTER_DATA_OF_SELETED_DAY, 'otherType'])) {
        // register , update work year month day 
        $data = isset($_GET['data']) ? htmlspecialchars_decode($_GET['data']) : null;
        $result = $kintaiRegDAO->update($data, $uidCurrent);
        // check this month data exists ? -> not current month -> new resgister 
        if ($result === 1) {
                returnValueTemplate($result); 
        } else {
                // add new tbl_worktime and tblworkmonth 
                if (!$checkFlagAddedNewData) {
                        $RsInsertNewMonth = $kintaiRegDAO->insertNewMonth($data, $uidCurrent);
                        $RsInsertNewMonthly = $kintaiRegDAO->insertNewMonthly($data, $uidCurrent);
                        if ($RsInsertNewMonth === 1 && $RsInsertNewMonthly === 1) {
                                $result = $kintaiRegDAO->update($data, $uidCurrent);
                        }
                        $checkFlagAddedNewData = true;
                        return;
                }
                returnValueTemplate($result);
        }
} else if (isset($type_get) && in_array($type_get, [$TYPE_DELETE_DATA_OF_SELETED_DAY, 'otherType'])) {
        // Delete this data year month day 
        $data = isset($_GET['data']) ? htmlspecialchars_decode($_GET['data']) : null;
        $result = $kintaiRegDAO->delete($data, $uidCurrent);
        $result === 1 ? returnValueTemplate($result) : returnValueTemplate($ADD_DATA_ERROR);

} else if (isset($type_get) && in_array($type_get, [$TYPE_REGISTER_DATA_OF_MONTH, 'otherType'])) {
        $data = isset($_GET['data']) ? htmlspecialchars_decode($_GET['data']) : null;
        // check Insert - update ?
        $objectData = json_decode($data, true);
        $checkDataNotExists = $kintaiRegDAO->selectMonthly($objectData['workym'], $uidCurrent);
        $result = "";
        if ($checkDataNotExists === 1) { // update 
                $result = $kintaiRegDAO->updateMonthly($data, $uidCurrent);
                $result === 1 ? $UPDATE_DATA_MONTH_SUCCESS : $ADD_DATA_ERROR;
        } else if ($checkDataNotExists === 0) { // insert 
                $result = $kintaiRegDAO->insertMonthly($data, $uidCurrent);
                $result === 1 ? $INSERT_DATA_MONTH_SUCCESS : $ADD_DATA_ERROR;

        } else {
                $result = $ADD_DATA_ERROR;
        }
        returnValueTemplate($result);

} else if (isset($type_get) && in_array($type_get, [$TYPE_GET_DATA_KINMUHYO, 'otherType'])) {
        // Delete this data year month day 
        $data = isset($_GET['data']) ? htmlspecialchars_decode($_GET['data']) : null;
        $result = [];
        try {
                if ($data !== null) {
                        $workOfMonth = $kintaiRegDAO->getWorkOfMonth($data, $uidCurrent);
                        $totalWorkMonth = $kintaiRegDAO->getTotalWorkMonth($data, $uidCurrent);
                        //error_log("**Connect Error**" . var_dump($workOfMonth) , 0);
                        if ($workOfMonth !== null && $totalWorkMonth !== null) {
                                $result = array_merge($workOfMonth, $totalWorkMonth);
                                returnValueTemplate($result);
                                return;
                        } else if ($workOfMonth !== null && $totalWorkMonth === null) {

                                returnValueTemplate($WORK_MOTH_IS_MISSING);
                                return;
                        } else if ($workOfMonth === null && $totalWorkMonth !== null) {
                                returnValueTemplate($WORK_TIME_IS_MISSING);
                                return;
                        } else if ($workOfMonth === null && $totalWorkMonth === null) {
                                returnValueTemplate($WORK_TIME_MONTH_IS_MISSING);

                                return;
                        } else {
                                $result = $CONNECT_ERROR;
                                returnValueTemplate(null);
                                return;
                        }
                } else {
                        returnValueTemplate(null);
                }
        } catch (Exception $e) {

                returnValueTemplate(null);
                echo "Have Error" . $e->getMessage();
        }

} else if (isset($type_get) && in_array($type_get, [$TYPE_REGISTER_NEW_DATA_OF_MONTH, 'otherType'])) {
        // Delete this data year month day 
        $data = isset($_GET['data']) ? htmlspecialchars_decode($_GET['data']) : null;
        $result = "";
        if ($data !== null) {
                $result = $kintaiRegDAO->insertNewMonthly($data, $uidCurrent);
                $result === 1 ? $UPDATE_DATA_MONTH_SUCCESS : $ADD_DATA_ERROR;
        } else {
                $result = null;
        }
        returnValueTemplate($result);
} else if (isset($type_get) && in_array($type_get, [$TYPE_GETCURRENT_USER, 'otherType'])) {
        // get Current User
        $result = $kintaiRegDAO->selectById($uidCurrent);
        if ($result !== null) {
                returnValueTemplate($result);
        } else {
                returnValueTemplate(null);
        }
} else {
        // Handle other types of requests 
}
function returnValueTemplate($result)
{
        global $NO_DATA;
        global $ADD_DATA_ERROR;
        // ***  Delete Cache ***
        header('Content-Type: application/json');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        if ($result == null || empty($result) || $result == $ADD_DATA_ERROR) {
                echo json_encode($NO_DATA);
        } else {
                echo json_encode($result);
        }
        exit;
}

?>
