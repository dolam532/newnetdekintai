<?php

include('.././inc/dbconnect.php');
include('.././inc/query.php');
include('.././inc/message.php');
include('./repository/KinmuRepository.php');
include('kinmuDAO.php');
// include('../container.php');


//========================================================//
// <!-- kinmuDAOImpl.php ->   
//========================================================//
class kinmuDAOImpl implements kinmuDAO {

    private $container;

    public function __construct($container){
        $this->container = $container;
    }
    
    public function selectAll() {
        $kinmuRepository  = $this->container->get(KinmuRepository::class);
        return $kinmuRepository->selectAll();
    }
    public function selectById($id) {
        $kinmuRepository  = $this->container->get(KinmuRepository::class);
        return $kinmuRepository->selectById($id);
    }

    
    public function insert($object) {
        $kinmuRepository  = $this->container->get(KinmuRepository::class);
        return $kinmuRepository->insert($object);
    }

    
    public function insertMany($listObject) {
        $kinmuRepository  = $this->container->get(KinmuRepository::class);
        return $kinmuRepository->insertMany($listObject);
    }

    
    public function update($object) {
        $kinmuRepository  = $this->container->get(KinmuRepository::class);
        return $kinmuRepository->update($object);
    }

        
    public function updateMany($listObject) {
        $kinmuRepository  = $this->container->get(KinmuRepository::class);
        return $kinmuRepository->updateMany($listObject);
    }


    public function delete($listObject) {
        $kinmuRepository  = $this->container->get(KinmuRepository::class);
        return $kinmuRepository->delete($listObject);
    }

    public function deleteMany($listObject) {
        $kinmuRepository  = $this->container->get(KinmuRepository::class);
        return $kinmuRepository->deleteMany($listObject);
    }

}

?>
