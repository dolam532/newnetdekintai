<?php
include('.././inc/dbconnect.php');
include('.././inc/query.php');
include('.././inc/message.php');

session_start();
class KintaiRegRepository
{

    public function selectById($id)
    {
        //==// Action connect to db get data -> return value = Object 
        global $conn;
        global $QUERY_SELECT_USER_BY_ID;
        $stmt = $conn->prepare($QUERY_SELECT_USER_BY_ID);
        if ($stmt) {
            $stmt->bind_param('s', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $stmt->close();
            return [
                'user' => $user_list[0]
            ];
        } else {
            return null;
        }


    }

    public function insert($object)
    {

    }

    public function insertMany($listObject)
    {

    }

    public function delete($object)
    {

    }

    public function deleteMany($listObject)
    {

    }

    //==// Select work of month 
    public function getWorkOfMonth($year, $month, $uid)
    {
        // Action connect to db get data -> return value = Object 
        global $conn;
        global $QUERY_SELECT_WORKMD;
        // create statemennt
        $workymd = "$year/$month/01";
        $stmt = $conn->prepare($QUERY_SELECT_WORKMD);
        if ($stmt) {
            $stmt->bind_param('sss', $uid, $uid, $workymd);
            $stmt->execute();
            $result = $stmt->get_result();
            $result_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $stmt->close();
            if (!empty($result_list)) {
                return [
                    'workYmdList' => $result_list
                ];
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    //==// after update work of day => update work month 
    public function getTotalWorkMonth($year, $month, $uid)
    {


         global $conn;
        global $QUERY_SELECT_WORKYM;
        // create statemennt
        $workym = "$year$month";
        $stmt = $conn->prepare($QUERY_SELECT_WORKYM);
        if ($stmt) {
            $stmt->bind_param('sss', $uid, $uid, $workym);
            $stmt->execute();
            $result = $stmt->get_result();
            $result_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $stmt->close();
            if (!empty($result_list)) {
                return [
                    'workym' => $result_list
                ];
            } else {
                return null;
            }
        } else {
            return null;
        }

    }
}


?>