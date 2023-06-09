<?php
include('.././inc/dbconnect.php');
include('.././inc/query.php');
include('.././inc/message.php');

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
    public function insertMany($data, $uid)
    {
        // Action connect to db get data -> return value = Object
        global $conn;
        $listObject = json_decode($data, true);
        global $QUERY_INSERT_MANY_WORK_OF_MONTH;
        $affected_rows = 0;
        try {
            $stmt = $conn->prepare($QUERY_INSERT_MANY_WORK_OF_MONTH);
            if ($stmt) {
                foreach ($listObject as $object) {
                    $stmt->bind_param('sss', $uid, $object['genid'], $object['workymd']);
                    $stmt->execute();
                    $affected_rows += $stmt->affected_rows;
                }
                $stmt->close();
                // check success line
                if ($affected_rows > 0) {
                    return 1;
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } catch (Exception $e) {
            return null;
        }

    }

    // inserttnew month to workyearmonth day
    public function insertNewMonth($year, $month, $uid)
    {
        // Action connect to db get data -> return value = Object
        global $conn;
        global $QUERY_INSERT_NEW_WORK_OF_MONTH;
        global $DEFAULT_GENBA_ID;
        $affected_rows = 0;
        $workymd = "$year/$month/01";
        // add query
        $query =  $QUERY_INSERT_NEW_WORK_OF_MONTH ;
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, intval(substr($workymd, 5, 2)), intval(substr($workymd, 0, 4)));
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $day = str_pad($i, 2, '0', STR_PAD_LEFT);
            $query .= " ('$uid', '$DEFAULT_GENBA_ID', CONCAT(SUBSTRING('$workymd', 1, 8), '$day'), null, null, null, null, null, null, null, null, null, null, null, null, null, null, NOW()),";
        }
        $query = rtrim($query, ','); // clear last , 
        error_log($query , 0);
        try {
            $stmt = $conn->prepare($query);
            if ($stmt) {
                $stmt->execute();
                $affected_rows += $stmt->affected_rows;
                $stmt->close();
                // check success line
                if ($affected_rows > 0) {
                    return 1;
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } catch (Exception $e) {
            return null;
        }
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
