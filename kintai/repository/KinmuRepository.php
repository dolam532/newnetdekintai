<?php
include('.././inc/dbconnect.php');
include('.././inc/query.php');
include('.././inc/message.php');
class KinmuRepository
{
    public function selectAll()
    {
        global $conn;
        global $QUERY_GET_ALL_KINTAI;
        $stmt = $conn->prepare($QUERY_GET_ALL_KINTAI);
        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();
            $genba_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $stmt->close();

            if (count($genba_list) > 0) {
                return [
                    'genbaList' => $genba_list
                ];
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function selectById($id)
    {
    }

    public function insert($object)
    {
        global $conn;
        $object = json_decode($object, true);
        global $QUERY_INSERT_KINMU;
        $affected_rows = 0;
        try {
            $stmt = $conn->prepare($QUERY_INSERT_KINMU);
            if ($stmt) {
                $stmt->bind_param(
                    'sssssss',
                    $object['genbaname'],
                    $object['workstrtime'],
                    $object['workendtime'],
                    $object['offtime1'],
                    $object['offtime2'],
                    $object['bigo'],
                    $object['use_yn']
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
    public function insertMany($listObject)
    {
    }
    public function update($object)
    {
        global $conn;
        $object = json_decode($object, true);
        global $QUERY_UPDATE_KINMU;
        $affected_rows = 0;
        try {
            $stmt = $conn->prepare($QUERY_UPDATE_KINMU);
            if ($stmt) {
                $stmt->bind_param(
                    'ssssssss',
                    $object['genbaname'],
                    $object['workstrtime'],
                    $object['workendtime'],
                    $object['offtime1'],
                    $object['offtime2'],
                    $object['bigo'],
                    $object['use_yn'],
                    $object['genid']
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
    public function updateMany($listObject)
    {
    }
    public function delete($object)
    {
        global $conn;
        $object = json_decode($object, true);
        global $QUERY_DELETE_KINMU;
        $affected_rows = 0;
        try {
            $stmt = $conn->prepare($QUERY_DELETE_KINMU);
            if ($stmt) {
                $stmt->bind_param(
                    's',
                    $object['genid']
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

    public function deleteMany($listObject)
    {
    }
}
