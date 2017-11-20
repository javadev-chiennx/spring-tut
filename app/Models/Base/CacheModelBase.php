<?php
namespace App\Models\Base;
use Exception;
use App\Adapter\CacheAccessor;

class CacheModelBase extends ModelBase {
    protected $primary_key="id";
    protected $cacheConnector;

    public function __construct($name, $primary_key)
    {
        parent::__construct($name);
        $this->cacheConnector=new CacheAccessor();
        $this->primary_key=$primary_key;
    }

    /*public function getObjectsByField($fields = array())
    {
        if(array_key_exists($this->primary_key,$fields)) {
            if($this->existCache($fields[$this->primary_key])) {
                $items = array(0=>$this->getFromCache($fields[$this->primary_key]));
                $this->analyticCacheIds($items);
                return $items;
            }
        }
        $result = parent::getObjectsByField($fields); // TODO: Change the autogenerated stub
        foreach ($result as $item) {
            // add to cache
            $this->addToCache($item[$this->primary_key],$item);

            // analytic reference ids
            $this->analyticCacheIds($item);
        }
        return $result;
    }*/

    /**
     * Check exist key
     * @param $key
     * @return bool
     */
    public function existCache($key) {
        $keyTableName=$this->tableName;
        return $this->cacheConnector->exists($keyTableName,$key);
    }

    public function getFromCache($key)
    {
        $keyTableName=$this->tableName;
        return $this->cacheConnector->exists($keyTableName,$key);
    }

    /**
     * @param $objects : the complex object need to analytic and get reference objects
     * @throws Exception : throw exception if child class do not implemented this function
     */
    protected function analyticCacheIds($object) {
        //throw new Exception("analyticReferenceObjects function from {$this->table_name} has not yet implemented");
    }

    public function addToCache($key,$object,$action=null) {
        if($action!=null) {
            //call back to update info cache
            $this->addToCacheCallBack($object,$action);
        }
        $keyTableName=$this->tableName;
        $this->cacheConnector->set($keyTableName,$key,$object);
    }

    private function addToCacheCallBack($object,$action) {
        if($action==ModelBase::ACTION_INSERT) {
            $this->insertToInfoCache($object);
        } elseif($action==ModelBase::ACTION_UPDATE) {
            $this->updateToInfoCache($object);
        }
    }

    protected function insertToInfoCache($object) {
        return;
    }
    protected function updateToInfoCache($object) {
        return;
    }
}