<?php

include('.././inc/dbconnect.php');
include('.././inc/query.php');
include('.././inc/message.php');
include('./repository/KintaiRegRepository.php');
include('KintaiRegDAO.php');
// include('../container.php');


//========================================================//
// <!-- KintaiRegDaoImpl.php ->   
//========================================================//
class KintaiRegDAOImpl implements KintaiRegDAO {

    private $container;

    public function __construct($container){
        $this->container = $container;
    }
  
    public function selectById($id) {
        $kintaiRegRepository  = $this->container->get(KintaiRegRepository::class);
        return $kintaiRegRepository->selectById($id);
    }

    public function insert($object , $uid){
        $kintaiRegRepository  = $this->container->get(KintaiRegRepository::class);
        return $kintaiRegRepository->insert($object);
    }

    public function insertMany($listObject , $uid){
        $kintaiRegRepository  = $this->container->get(KintaiRegRepository::class);   
        return $kintaiRegRepository->insertMany($listObject , $uid);
    }

    public function update($object ,  $uid){
        $kintaiRegRepository  = $this->container->get(KintaiRegRepository::class);   
        return $kintaiRegRepository->update($object ,  $uid);
    }

    public function updateMany($listObject ,$uid ){
        $kintaiRegRepository  = $this->container->get(KintaiRegRepository::class);   
        return $kintaiRegRepository->updateMany($listObject ,$uid);
    }



    public function delete($object , $uid){
        $kintaiRegRepository  = $this->container->get(KintaiRegRepository::class);
        return $kintaiRegRepository->delete($object , $uid);
    }

    public function deleteMany($listObject , $uid){
        $kintaiRegRepository  = $this->container->get(KintaiRegRepository::class);
        return $kintaiRegRepository->deleteMany($listObject , $uid);
    }

    public function getWorkOfMonth($data, $uid) {
        $kintaiRegRepository  = $this->container->get(KintaiRegRepository::class);
        return $kintaiRegRepository->getWorkOfMonth($data, $uid);
    }

    public function getTotalWorkMonth($data , $uid) {
        $kintaiRegRepository  = $this->container->get(KintaiRegRepository::class);
        return $kintaiRegRepository->getTotalWorkMonth($data , $uid);
    }
    
    public function insertNewMonth($data, $uid){
        $kintaiRegRepository  = $this->container->get(KintaiRegRepository::class);   
        return $kintaiRegRepository->insertNewMonth($data, $uid);
    }


    //==============================// 
    //==Table Table workmonth ======// 
    //==============================// 

    public function selectMonthly($workym ,$uid ){
        $kintaiRegRepository  = $this->container->get(KintaiRegRepository::class);   
        return $kintaiRegRepository->selectMonthly($workym ,$uid);
    }

    public function insertMonthly($object ,$uid ){
        $kintaiRegRepository  = $this->container->get(KintaiRegRepository::class);   
        return $kintaiRegRepository->insertMonthly($object ,$uid);
    }
    public function updateMonthly($object ,$uid ){
        $kintaiRegRepository  = $this->container->get(KintaiRegRepository::class);   
        return $kintaiRegRepository->updateMonthly($object ,$uid);
    }










}

?>
