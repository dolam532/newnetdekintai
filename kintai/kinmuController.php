<?php
session_start();
include '../inc/const.php';
include '../inc/query.php';
include('../inc/message.php');
include('./container.php');
include('dao/kinmuDAOImpl.php');

// use container.php
$customContainer = new Container();
$kinmuDAO = $customContainer->get(KinmuRepository::class);
$type_get = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : null;

// $_SESSION['auth'] == false;
$uidCurrent = $_SESSION['auth_uid'];

if (isset($type_get) && in_array($type_get, [$TYPE_GET_ALL_DATA_KINMU, 'otherType'])) { // 
        // get data kinmu
        $result = $kinmuDAO->selectAll();
        $result === null ? returnValueTemplate(null) : returnValueTemplate($result);
} else if (isset($type_get) && in_array($type_get, [$TYPE_UPDATE_DATA_KINMU, 'otherType'])) {
        $data = isset($_GET['data']) ? htmlspecialchars_decode($_GET['data']) : null;
        $result = $kinmuDAO->update($data);
        $result === 1 ? returnValueTemplate($result) : returnValueTemplate($ADD_DATA_ERROR);
} else if (isset($type_get) && in_array($type_get, [$TYPE_DELETE_DATA_KINMU, 'otherType'])) {
        $data = isset($_GET['data']) ? htmlspecialchars_decode($_GET['data']) : null;
        $result = $kinmuDAO->delete($data);
        $result === 1 ? returnValueTemplate($result) : returnValueTemplate($ADD_DATA_ERROR);
} else if (isset($type_get) && in_array($type_get, [$TYPE_INSERT_DATA_KINMU, 'otherType'])) {
        $data = isset($_GET['data']) ? htmlspecialchars_decode($_GET['data']) : null;
        $result = $kinmuDAO->insert($data);
        $result === 1 ? returnValueTemplate($result) : returnValueTemplate($ADD_DATA_ERROR);
} else {
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
