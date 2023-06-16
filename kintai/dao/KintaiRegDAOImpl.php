<?php
include('.././inc/dbconnect.php');
include('.././inc/query.php');
include('.././inc/message.php');
include('./repository/KintaiRegRepository.php');
include(__DIR__ . './KintaiRegDAO.php');


//========================================================//
// <!-- KintaiReg.php ->   
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

    public function getWorkOfMonth($year , $month , $uid) {
        $kintaiRegRepository  = $this->container->get(KintaiRegRepository::class);
        return $kintaiRegRepository->getWorkOfMonth($year , $month , $uid);
    }

    public function getTotalWorkMonth($year , $month , $uid) {
        $kintaiRegRepository  = $this->container->get(KintaiRegRepository::class);
        return $kintaiRegRepository->getTotalWorkMonth($year , $month , $uid);
    }
    
    public function insertNewMonth($year, $month, $uid){
        $kintaiRegRepository  = $this->container->get(KintaiRegRepository::class);   
        return $kintaiRegRepository->insertNewMonth($year, $month, $uid);
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
