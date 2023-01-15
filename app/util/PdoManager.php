<?php

namespace App\Util;

use PDOException;

class PdoManager
{
    public const ORACLE_TO_DATETIME_TIMESTAMP = 'd/m/Y H:i:s,u';

    private $db_settings;
    private $pdo_dsn;

    public function __construct($dbconfig_filename = DBCONFIG_DEFAULT_FILENAME)
    {
        $db_settings_path = APP_CONFIG . $dbconfig_filename;
        $this->db_settings = include($db_settings_path);
        $this->pdo_dsn = PdoManager::getPdoDsn($this->db_settings);
    }

    /**
     * Insert les données $data dans la table $table.
     *
     * @param \PDO $pdo
     * @param string $table
     * @param array $fields
     * @param array $data
     * @return void
     */
    public static function insertMultipleRows($pdo, $table, $fields, $data)
    {
        $binds_placeholder = implode(', ', array_fill(0, count($fields), '?'));
        $fields = implode(', ', $fields);
        $stmt = $pdo->prepare("INSERT INTO $table ($fields) VALUES ($binds_placeholder)");

        $ret = [];
        try {
            $pdo->beginTransaction();
            foreach ($data as $row) {
                $stmt->execute($row);
                $ret[] = $stmt->rowCount();
            }
            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollback();
            throw $e;
        }

        return $ret;
    }

    /**
     * Associe les valeurs de $binds avec PDO::bindValue en utilisant les
     * données dans $values et dans $data_types.
     *
     * @param PDOStatement $stmt
     * @param array $binds
     * @param array $data_types
     * @return void
     */
    public static function bindMultipleValues(&$stmt, $values, $data_types = [])
    {
        foreach ($values as $bind_name => $value) {
            $data_type = $data_types[$bind_name] ?? \PDO::PARAM_STR;
            $stmt->bindValue($bind_name, $value, $data_type);
        }
    }

    public static function inClause($array)
    {
        return str_repeat('?,', count($array) - 1) . '?';
    }

    /**
     * Modifie la valeur des clées des enfants de l'array donnée en paramètre.
     * La nouvelle valeur de la clé utilisée sera la valeur de chaque enfant[$id_name].
     * Si $keep_value vaut true, enfant[$id_name] est retiré.
     *
     * Retourne false si une valeur enfant[$id_name] manque.
     *
     * @param array $array
     * @param string $id_name
     * @param bool $keep_value
     * @return array|bool
     */
    public static function rekeyChildById(array $array, string $id_name, $keep_value = false)
    {
        $new_array = [];
        foreach ($array as $key => $value) {
            foreach ($value as $c_key => $c_value) {
                if (
                    !array_key_exists($id_name, $c_value)
                    && array_key_exists($key, $new_array)
                    && !array_key_exists($c_value[$id_name], $new_array[$key])
                ) {
                    return false;
                }

                $new_id = $c_value[$id_name];
                $new_array[$key][$new_id] = $c_value;

                if (!$keep_value) {
                    unset($new_array[$key][$new_id][$id_name]);
                }
            }
        }

        return $new_array;
    }

    /**
     * Retourne une instance de PDO en utilisant les données de configuration
     *
     * @return \PDO
     */
    public function getPdo(): \PDO
    {
        $user = $this->db_settings['username'] ?? null;
        $pass = $this->db_settings['password'] ?? null;
        $options = $this->db_settings['options'] ?? [];

        try {
            $pdo = new \PDO($this->pdo_dsn, $user, $pass, $options);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }

        return $pdo;
    }

    /**
     * Retourne la string DSN de connection utilisé pour l'instanciation d'un PDO
     *
     * @param array $db_settings
     * @return string
     */
    private static function getPdoDsn($db_settings): string
    {
        $pdo_dsn = '';

        if (!isset($db_settings['driver'])) {
            throw new \Exception('Invalid database settings', 1);
        } elseif ($db_settings['driver'] === 'oci') {

            $host = ($db_settings['host']);
            $dbname = ($db_settings['dbname']);
            $tns = '(DESCRIPTION=
                (ADDRESS_LIST =
                        (ADDRESS = (PROTOCOL = TCP)(HOST = ' . $host . ')(PORT = 1521))
                )
                (CONNECT_DATA=
                    (SID=' . $dbname . ')
                )
            );';

            $pdo_dsn = 'oci:dbname=' . $tns;

            if (isset($db_settings['charset'])) {
                $pdo_dsn .= 'charset=' . $db_settings['charset'] . ';';
            }
        } else {
            if (isset($db_settings['host'])) {
                $pdo_dsn .= $db_settings['driver'] . ':host=' . $db_settings['host'] . ';';
            } else {
                throw new \Exception('Invalid database settings', 1);
            }

            if (isset($db_settings['dbname'])) {
                $pdo_dsn .= 'dbname=' . $db_settings['dbname'] . ';';
            } else {
                throw new \Exception('Invalid database settings', 1);
            }

            if (isset($db_settings['port'])) {
                $pdo_dsn .= 'port=' . $db_settings['port'] . ';';
            }

            if (isset($db_settings['charset'])) {
                $pdo_dsn .= 'charset=' . $db_settings['charset'] . ';';
            }
        }

        return $pdo_dsn;
    }
}
