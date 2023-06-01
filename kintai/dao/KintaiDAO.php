<?php

//========================================================//
// <!-- KintaiDAO  --> 
//========================================================//
interface KintaiDAO {
    public function selectById($id) ;

    public function insert($object);

    public function insertMany($listObject);

    public function delete($object);

    public function deleteMany($listObject);

    public function getWorkOfMonth($year , $month , $uid) ;

}


?>