<?php
include('.././inc/dbconnect.php');
include('.././inc/query.php');
include('.././inc/message.php');
// include('../container.php');
class KinmuRepository{
    public function selectAll(){
        
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

    public function selectById($id){

    }

    public function insert($object){

    }
    public function insertMany($listObject){

    }
    public function update($object){

    }
    public function updateMany($listObject){

    }
    public function delete($object){

    }
    public function deleteMany($listObject){ 

    }



}
?>