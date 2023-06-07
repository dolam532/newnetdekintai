<?php

//========================================================//
// <!-- KintaiDAO  --> 
//========================================================//
interface KintaiRegDAO {
    public function selectById($id) ;

    public function insert($object);

    public function insertMany($listObject ,  $uid);  
    public function insertNewMonth($year, $month, $uid);

    public function delete($object);

    public function deleteMany($listObject);

    public function getWorkOfMonth($year , $month , $uid) ;

    public function getTotalWorkMonth($year , $month , $uid) ;

}


?>
