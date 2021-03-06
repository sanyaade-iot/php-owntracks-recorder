<?php

namespace OwntracksRecorder\Database;

use \OwntracksRecorder\Database\AbstractDatabase;

class SQLite extends AbstractDatabase
{
    public function __construct($db, $hostname = null, $username = null, $password = null, $prefix = '')
    {
        $this->db = new \PDO('sqlite:' . $db);
        $this->prefix = '';
        $this->execute('PRAGMA foreign_keys = ON;');
    }

    protected function query(string $sql, array $params = array()): array
    {
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->execute($params);

        $result = array();
        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            // Loop through results here $data[]
            $result[] = $data;
        }

        $stmt->closeCursor();
        return $result;
    }

    protected function execute(string $sql, array $params = array()): bool
    {
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $result = $stmt->execute($params);
        if ($result) {
            $stmt->closeCursor();
        }
        return $result;
    }

    public function beginTransaction()
    {
        $this->db->beginTransaction();
    }

    public function commitTransaction()
    {
        $this->db->commit();
    }
}
