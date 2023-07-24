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
    public function getWorkOfMonth($data, $uid);
    public function getTotalWorkMonth($data, $uid);
    public function insertNewMonth($data, $uid);



    //==============================// 
    //==Table Table workmonth ======// 
    //==============================// 
    public function updateMonthly($object, $uid);
    public function selectMonthly($workym, $uid);
    public function insertMonthly($object, $uid);



}


?>