<?php
// KinmuDAO
interface KinmuDAO
{
    public function selectAll();
    public function selectById($id);
    public function insert($object);
    public function insertMany($listObject);
    public function update($object);
    public function updateMany($listObject);
    public function delete($object);
    public function deleteMany($listObject);
}
