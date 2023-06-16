<?php

//========================================================//
// <!-- KintaiDAO  --> 
//========================================================//
interface KintaiRegDAO
{
    public function selectById($id);
    public function insert($object, $uid);
    public function insertMany($listObject, $uid);
    public function update($object, $uid);
    public function updateMany($listObject, $uid);
    public function delete($object, $uid);
    public function deleteMany($listObject, $uid);
    public function getWorkOfMonth($year, $month, $uid);
    public function getTotalWorkMonth($year, $month, $uid);
    public function insertNewMonth($year, $month, $uid);



    //==============================// 
    //==Table Table workmonth ======// 
    //==============================// 
    public function updateMonthly($object, $uid);
    public function selectMonthly($workym, $uid);
    public function insertMonthly($object, $uid);



}


?>