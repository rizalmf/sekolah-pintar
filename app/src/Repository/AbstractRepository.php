<?php
namespace app\src\Repository;

use app\config\eloquent\DB;

abstract class AbstractRepository
{
    /**
     * Manual logging
     * 
     * @var Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * Plain PDO
     * 
     * @var PDO
     */
    protected $pdo;

    public function __construct()
    {
        $this->pdo = DB::connection()->getPdo();
        $this->logger = DB::getCustomLogger();
    }

    /**
     * return raw query
     * bug ? value. fixing pakai foreach.. next
     * 
     * @var DB::table
     * @return string
     */
    public function dump($table)
    {
        $query = $table->toSql();
        $bindings = $table->getBindings();
        $bind = 0;

        while ($bind < count($bindings)) {
            if ($i = strpos($query, '?')) {
                $query[$i] = $bindings[$bind];
            }
            $bind++;
        }

        return $query;
    }

    /**
     * @return array of assoc array
     */
    protected function parseArray($objArray){
        $parsed = array();
        foreach ($objArray as $val) {
            $parsed[] = (array) $val;
        }

        return $parsed;
    }

    /**
     * @return array of objects
     */
    protected function parseObjArray($array){
        $parsed = array();
        foreach ($array as $val) {
            $parsed[] = (object) $val;
        }
        
        return $parsed;
    }
}
