<?php

namespace App\Model;


class BaseModel
{
    protected $pdo;

    public function __construct($dbconfig_filename = DBCONFIG_DEFAULT_FILENAME) {
        $this->pdo = $this->getPdo($dbconfig_filename);
    }

    /**
     * Retourne un objet PDO instancié en fonction du fichier config/db_config.php
     *
     * @return \PDO
     */
    private function getPdo($dbconfig_filename) {
        return (new \App\Util\PdoManager($dbconfig_filename))->getPdo();
    }
}
