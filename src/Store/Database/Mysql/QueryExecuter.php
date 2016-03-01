<?php
/**
 * Created by PhpStorm.
 * User: yuzhenfan
 * Date: 16/3/1
 * Time: 下午8:09
 */
namespace Zan\Framework\Store\Database\Mysql;

class QueryExecuter
{
    private $db;

    private $sqlMap;

    public function __construct()
    {
        $this->init();
    }

    private function init()
    {
        $this->setDb();
    }

    private function setDb()
    {
        if (null == $this->db) {
            //todo
            $this->db = new object;
        }
        return $this->db;
    }

    public function getDb()
    {
        return $this->db;
    }

    public function insert()
    {

    }

    public function update()
    {

    }

    public function delete()
    {

    }

    public function query()
    {

    }

    private function validate()
    {

    }

    private function getSqlMap()
    {
        if (null == $this->sqlMap) {
            $this->createSqlMap();
        }
        return $this->sqlMap;
    }

    private function createSqlMap()
    {
        $this->sqlMap = new SqlMap();
        return $this;
    }







}