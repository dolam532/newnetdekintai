<?php
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
$type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : null;


//===// Check Login****
// Step 1 : Check login session -> 
// ........ AFTER 
// Get uid in Session 
$uidCurrent = 'admin';


// step 2 : 
if (isset($_GET['type']) && in_array($_GET['type'], [$TYPE_GET_WORK_YEAR_MONTH_DAY, 'otherType'])) { // get data for work year month day

        $result = $kintaiRegDAO->getWorkOfMonth($year, $month, $uidCurrent);
        header('Content-Type: application/json');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        if ($result == null || empty($result)) {
                echo json_encode($KINTAI_NODATA);
        } else {
                echo json_encode($result);
        }
        exit;

} else if (isset($_GET['type']) && in_array($_GET['type'], [$TYPE_GET_WORK_YEAR_MONTH, 'otherType'])) { // get data for year month 

        $result = $kintaiRegDAO->getTotalWorkMonth($year, $month, $uidCurrent);
        header('Content-Type: application/json');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        if ($result == null || empty($result)) {
                echo json_encode($KINTAI_NODATA);
        } else {
                echo json_encode($result);
        }

        exit;


} else {
        // type = Other 
}


?>