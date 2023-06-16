<?php
session_start();
// Include const.php
require_once '../inc/const.php';
require_once '../inc/query.php';
// Include kintaoDAO -> Data access Object 
include('../inc/message.php');
include('dao/KintaiRegDAOImpl.php');
// import DI 
include('../vendor/autoload.php');
use DI\Container;

// create iinstance -> reference to KintaiRegRepository
$container = new Container();
$kintaiRegDAO = $container->get(KintaiRegRepository::class);

//=*******WARNING**********// Now is user id = 'admin' to dev
//==// AFTER -> Check Login 


// ====use demo ==== ///

// =========== ///

//  Config type=getKintaiymd

// Get data from db with id = id , year = CbbYear , Month = cbbMonth 
$year = isset($_GET['year']) ? htmlspecialchars($_GET['year']) : null;
$month = isset($_GET['month']) ? htmlspecialchars($_GET['month']) : null;
$type_get = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : null;


// $type_post = isset($_POST['type']) ? htmlspecialchars($_POST['type']) : null;
//===// Check Login****
// Step 1: Check login session
// ........ AFTER 
// Get uid in Session 

// $_SESSION['auth'] == false;
$uidCurrent = $_SESSION['auth_uid'];

// GET request
if (isset($type_get) && in_array($type_get, [$TYPE_GET_WORK_YEAR_MONTH_DAY, 'otherType'])) { // get data for work year month day
        // get data work year month 
        $result = $kintaiRegDAO->getWorkOfMonth($year, $month, $uidCurrent);
        returnValueTemplate($result);
} else if (isset($type_get) && in_array($type_get, [$TYPE_GET_WORK_YEAR_MONTH, 'otherType'])) { // get data for year month 
        // ??? now not use .... -> get total of month => kyukaMonthly.php ???
        $result = $kintaiRegDAO->getTotalWorkMonth($year, $month, $uidCurrent);
        returnValueTemplate($result);

} else if (isset($type_get) && in_array($type_get, [$TYPE_INSERT_MISSING_WORK_YEAR_MONTH_DAY, 'otherType'])) {
        // insert data of month if data missing
        $data = isset($_GET['data']) ? htmlspecialchars_decode($_GET['data']) : null;
        $result = $kintaiRegDAO->insertMany($data, $uidCurrent);
        $result === 1 ? returnValueTemplate($result) : returnValueTemplate($ADD_DATA_ERROR_KINTAI);

} else if (isset($type_get) && in_array($type_get, [$TYPE_INSERT_NEW_WORK_YEAR_MONTH_DAY, 'otherType'])) {
        // add current month to db if current month data is not exists in db 
        $result = $kintaiRegDAO->insertNewMonth($year, $month, $uidCurrent);
        $result === 1 ? returnValueTemplate($result) : returnValueTemplate($ADD_DATA_ERROR_KINTAI);

} else if (isset($type_get) && in_array($type_get, [$TYPE_REGISTER_DATA_OF_SELETED_DAY, 'otherType'])) {
        // register , update work year month day 
        $data = isset($_GET['data']) ? htmlspecialchars_decode($_GET['data']) : null;
        $result = $kintaiRegDAO->update($data, $uidCurrent);
        $result === 1 ? returnValueTemplate($result) : returnValueTemplate($ADD_DATA_ERROR_KINTAI);

} else if (isset($type_get) && in_array($type_get, [$TYPE_DELETE_DATA_OF_SELETED_DAY, 'otherType'])) {
        // Delete this data year month day 
        $data = isset($_GET['data']) ? htmlspecialchars_decode($_GET['data']) : null;
        $result = $kintaiRegDAO->delete($data, $uidCurrent);
        $result === 1 ? returnValueTemplate($result) : returnValueTemplate($ADD_DATA_ERROR_KINTAI);
        
} else if (isset($type_get) && in_array($type_get, [$TYPE_REGISTER_DATA_OF_MONTH, 'otherType'])) {
        $data = isset($_GET['data']) ? htmlspecialchars_decode($_GET['data']) : null;
        // check Insert - update ?
        $objectData = json_decode($data, true);
        $checkDataNotExists = $kintaiRegDAO->selectMonthly($objectData['workym'], $uidCurrent);
        $result = "";
        if($checkDataNotExists === 1) {  // update 
                $result = $kintaiRegDAO->updateMonthly($data, $uidCurrent);
                $result === 1 ? $UPDATE_DATA_MONTH_SUCCESS : $ADD_DATA_ERROR_KINTAI;

        } else if($checkDataNotExists === 0) {  // insert 
                $result = $kintaiRegDAO->insertMonthly($data, $uidCurrent);
                $result === 1 ? $INSERT_DATA_MONTH_SUCCESS : $ADD_DATA_ERROR_KINTAI;
        } else {
                $result = $ADD_DATA_ERROR_KINTAI;
        }
        returnValueTemplate($result);   
        // result == null => Net work Error , DB Error ...

    
} else {

        // Handle other types of requests
}


function returnValueTemplate($result)
{
        global $NO_DATA_KINTAI;
        global $ADD_DATA_ERROR_KINTAI;
        // ***  Delete Cache ***
        header('Content-Type: application/json');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        if ($result == null || empty($result) || $result == $ADD_DATA_ERROR_KINTAI ) {
                echo json_encode($NO_DATA_KINTAI);
        } else {
                echo json_encode($result);
        }
        exit;
}

?>