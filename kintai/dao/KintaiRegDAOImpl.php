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

    public function insert($object){
        $kintaiRegRepository  = $this->container->get(KintaiRegRepository::class);
        return $kintaiRegRepository->insert($object);
    }

    public function insertMany($listObject , $uid){
        $kintaiRegRepository  = $this->container->get(KintaiRegRepository::class);   
        return $kintaiRegRepository->insertMany($listObject , $uid);
    }
    public function insertNewMonth($year, $month, $uid){
        $kintaiRegRepository  = $this->container->get(KintaiRegRepository::class);   
        return $kintaiRegRepository->insertNewMonth($year, $month, $uid);
    }

    public function delete($object){
        $kintaiRegRepository  = $this->container->get(KintaiRegRepository::class);
        return $kintaiRegRepository->delete($object);
    }

    public function deleteMany($listObject){
        $kintaiRegRepository  = $this->container->get(KintaiRegRepository::class);
        return $kintaiRegRepository->deleteMany($listObject);
    }

    public function getWorkOfMonth($year , $month , $uid) {
        $kintaiRegRepository  = $this->container->get(KintaiRegRepository::class);
        return $kintaiRegRepository->getWorkOfMonth($year , $month , $uid);
    }

    public function getTotalWorkMonth($year , $month , $uid) {
        $kintaiRegRepository  = $this->container->get(KintaiRegRepository::class);
        return $kintaiRegRepository->getTotalWorkMonth($year , $month , $uid);
    }






}

?>