<?php

abstract class CDbHelper implements IDbHelper {

    /**
     *
     * @var CDbConnection CDbConnection
     */
    public $db;

    public function __construct() {
        $this->db = Yii::app()->db;
    }

    public function getName() {
        preg_match('/dbname=(.*?)(?=;|$)/i', $this->db->connectionString, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        } else {
            throw new CException(Yii::t('AdmindbModule.core', 'DB Name not found in CDbConnection::connectionString'));
        }
    }

    public function getHost() {
        preg_match('/host=(.*?)(?=;|$)/i', $this->db->connectionString, $matches);
        if (isset($matches[1])) {
            $host = $matches[1];
        } else {
            $host = 'localhost';
        }
        return $host;
    }

    public function getPort() {
        preg_match('/port=(.*?)(?=;|$)/i', $this->db->connectionString, $matches);
        if (isset($matches[1])) {
            $port = intval($matches[1]);
        } else {
            $port = 3306;
        }
        return $port;
    }

    public function getTables() {
        return $this->db->schema->tables;
    }

    /**
     *
     * @return array List of tables names in database
     */
    public function getTableNames() {
        return array_map(create_function('$table', 'return $table->name;'), $this->getTables());
    }

    /**
     * Truncate Tables or Database
     * @param string|array $table Table|Tables name or null to truncate all tables
     * @return boolean If success or not
     */
    public function truncate($table = null) {
        if ($table === null) {
            $table = $this->getTableNames();
        }

        $transaction = $this->db->beginTransaction();
        try {
            if (is_array($table)) {
                foreach ($table as $value) {
                    $this->db->createCommand()->truncateTable($value);
                }
            } else {
                $this->db->createCommand()->truncateTable($table);
            }
            $transaction->commit();
        } catch (CException $e) {
            $transaction->rollback();
            return false;
        }

        return true;
    }

    /**
     * Drop Tables
     * @param string|array $table Table|Tables name to drop
     * @return boolean If success or not
     */
    public function drop($table) {
        $transaction = $this->db->beginTransaction();
        try {
            if (is_array($table)) {
                foreach ($table as $value) {
                    $this->db->createCommand()->dropTable($value);
                }
            } else {
                $this->db->createCommand()->dropTable($table);
            }
            $transaction->commit();
        } catch (CException $e) {
            $transaction->rollback();
            return false;
        }

        return true;
    }

}