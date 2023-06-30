<?php
class Container
{
    private $instances = [];

    public function get($class)
    {
        if (!isset($this->instances[$class])) {
            $this->instances[$class] = $this->resolve($class);
        }

        return $this->instances[$class];
    }

    private function resolve($class)
    {
        // Logic
        switch ($class) {
            case KintaiRegDAOImpl::class:
                return new KintaiRegDAOImpl($this); 
            case KintaiRegRepository::class:
                return new KintaiRegRepository();
                
            case kinmuDAOImpl::class:
                return new kinmuDAOImpl($this);
            case KinmuRepository::class:
                return new KinmuRepository();

            //Other calss
            default:
                throw new Exception("Unknown class: $class");
        }
    }
}
?>