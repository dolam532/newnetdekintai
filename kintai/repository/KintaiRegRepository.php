<?php
include('.././inc/dbconnect.php');
include('.././inc/query.php');
include('.././inc/message.php');
// include('../container.php');

class KintaiRegRepository
{

    public function selectById($id)
    {
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

    public function insert($object, $uid)
    {
    }
    public function insertMany($listData, $uid)
    {
        // Action connect to db get data -> return value = Object
        global $conn;
        $listObject = json_decode($listData, true);
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

    public function update($object, $uid)
    {
        global $conn;
        $object = json_decode($object, true);
        global $QUERY_UPDATE_DATA_WORK_OF_YMD;
        $affected_rows = 0;
        try {
            $stmt = $conn->prepare($QUERY_UPDATE_DATA_WORK_OF_YMD);
            if ($stmt) {
                $stmt->bind_param(
                    'ssssssssssssssss', $object['daystarthh'], $object['daystartmm'], // 'ssssssssssssssssss'
                    $object['dayendhh'], $object['dayendmm'], $object['jobstarthh'],
                    $object['jobstartmm'], $object['jobendhh'], $object['jobendmm'],
                    $object['offtimehh'], $object['offtimemm'], $object['workhh'],
                    $object['workmm'], // $object['janhh'], $object['janmm'],  // iF INSERT ZANGYO
                    $object['comment'], $object['bigo'],
                    $uid, $object['selectedDate'] 
                );

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

    public function updateMany($listData, $uid)
    {
    }




    public function delete($object, $uid)
    {
        global $conn;
        $object = json_decode($object, true);
        global $QUERY_DELETE_DATA_WORK_OF_YMD;
        $affected_rows = 0;
        try {
            $stmt = $conn->prepare($QUERY_DELETE_DATA_WORK_OF_YMD);
            if ($stmt) {
                $stmt->bind_param(
                    'ss',
                    $uid, $object['selectedDate']
                );
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

    public function deleteMany($listData, $uid)
    {

    }

    //==// Select work of month 
    public function getWorkOfMonth($data, $uid)
    {
        global $conn;
        global $QUERY_SELECT_WORKMD;
        $object = json_decode($data, true);
        // create statemennt
        $workymd = substr_replace($object['workym'], '/', 4, 0) . '/01';

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
    public function getTotalWorkMonth($data, $uid)
    {
        global $conn;
        global $QUERY_SELECT_WORKYM;
        $object = json_decode($data, true);
        // create statemennt
        $workym = $object['workym'];
        $stmt = $conn->prepare($QUERY_SELECT_WORKYM);
        if ($stmt) {
            $stmt->bind_param('sss', $uid, $uid, $workym);
            $stmt->execute();
            $result = $stmt->get_result();
            $result_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $stmt->close();
            if (!empty($result_list)) {
                return [
                    'workym' =>$result_list[0]
                ];
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    // inserttnew month to workyearmonth day
    public function insertNewMonth($data, $uid)
    {
        // Action connect to db get data -> return value = Object
        global $conn;
        global $QUERY_INSERT_NEW_WORK_OF_MONTH;
        global $DEFAULT_GENBA_ID;
        $affected_rows = 0;
        $object = json_decode($data, true);
        $workymd = substr_replace($object['workym'], '/', 4, 0) . '/01';
        
        // add query
        $query = $QUERY_INSERT_NEW_WORK_OF_MONTH;
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, intval(substr($workymd, 5, 2)), intval(substr($workymd, 0, 4)));
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $day = str_pad($i, 2, '0', STR_PAD_LEFT);
            $query .= " ('$uid', '$DEFAULT_GENBA_ID', CONCAT(SUBSTRING('$workymd', 1, 8), '$day'), null, null, null, null, null, null, null, null, null, null, null, null, null, null, NOW()),";
        }
        $query = rtrim($query, ','); // clear last , 

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

    //==============================// 
    //==Table Table workmonth ======// 
    //==============================// 

    public function updateMonthly($object, $uid)
    {
        global $conn;
        $object = json_decode($object, true);
        global $QUERY_UPDATE_DATA_WORK_OF_YM;
        $affected_rows = 0;
        try {
            $stmt = $conn->prepare($QUERY_UPDATE_DATA_WORK_OF_YM);
            if ($stmt) {
                $stmt->bind_param(
                    'sssssssssssssss', $object['genid'], $object['jobhour'],  // 'sssssssssssssssssss'
                    $object['jobminute'], $object['jobhour2'], $object['jobminute2'],
                    // $object['janhour'], $object['janminute'], $object['janhour2'], $object['janminute2'],  // if insert zangyo
                     $object['workdays'], $object['workdays2'],
                    $object['jobdays'], $object['jobdays2'], $object['offdays'],
                    $object['delaydays'],$object['earlydays'], $object['bigo'],
                     $uid, $object['workym']
                );
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

    public function selectMonthly($workym, $uid)
    {
        global $conn;
        global $QUERY_SELECT_WORK_OF_YM;
        try {
            $stmt = $conn->prepare($QUERY_SELECT_WORK_OF_YM);
            if ($stmt) {
                $stmt->bind_param('ss', $workym, $uid);
                $stmt->execute();
                $stmt->store_result(); 
                $affected_rows = $stmt->num_rows; 
                $stmt->close();
                if ($affected_rows > 0) {
                    return 1; 
                } else {
                    return 0; 
                }
            } else {
                return null;
            }
        } catch (Exception $e) {
            return null;
        }
    }

    public function insertMonthly($object ,$uid ){
        global $conn;
        $object = json_decode($object, true);
        global $QUERY_INSERT_NEW_WORK_OF_YM;
        $affected_rows = 0;
        try {
            $stmt = $conn->prepare($QUERY_INSERT_NEW_WORK_OF_YM);
            if ($stmt) {
                $stmt->bind_param(
                    'sssssssssssssss',  $object['workym'],$uid ,$object['genid'], // 'sssssssssssssssssss'
					$object['jobhour'],$object['jobminute'], $object['jobhour2'], 
					$object['jobminute2'],
                    // $object['janhour'], $object['janminute'], $object['janhour2'],$object['janminute2'], if insert zangyo
                    $object['workdays'], 
					$object['workdays2'],$object['jobdays'], $object['jobdays2'], 
					$object['offdays'], $object['delaydays'],$object['earlydays'], $object['bigo']
                );
                $stmt->execute();
                $affected_rows += $stmt->affected_rows;
                $stmt->close();
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

}


?>