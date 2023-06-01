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

// Get data from db with id = id , year = CbbYear , Month = cbbMonth 

$year = isset($_GET['year']) ? htmlspecialchars($_GET['year']) : null;
$month = isset($_GET['month']) ? htmlspecialchars($_GET['month']) : null;
$type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : null;

//===// Check Login****


//===// LoginSuccess-> Getdata
if (isset($_GET['type']) && in_array($_GET['type'], ['getKintaiymd', 'otherType'])) {
        // Step 1 : Check login session -> 


        // step 2 :
        $result = $kintaiRegDAO->getWorkOfMonth($year , $month, 'admin');
        header('Content-Type: application/json');
        if($result == null || empty($result) ) {
             
             echo json_encode("NO_DATA");
        } else {
             echo json_encode($result);
        }
        exit;
} else {

}


?>