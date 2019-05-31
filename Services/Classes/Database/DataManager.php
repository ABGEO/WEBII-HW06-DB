<?php

namespace Database;

use PDO;
use PDOException;

class DataManager
{
    /** @var PDO $_connection */
    private $_connection;


    /**
     * DataManager constructor.
     *
     * @param string $database
     * @param string $user
     * @param string $password
     * @param string $host
     */
    public function __construct(string $database, string $user,
        string $password, string $host = 'localhost'
    ) {
        try {
            $conn = new PDO(
                "mysql:host={$host};dbname={$database}", $user, $password
            );

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->_connection = $conn;
        } catch (PDOException $e) {
            echo $e->getCode() . ': ' . $e->getMessage();
        }
    }

    /**
     * Select data current database instance.
     *
     * @param array      $columns  DB column names.
     * @param string     $table    DB table name.
     * @param array|null $where    Where statement.
     * @param array|null $order_by Order statement.
     * @param int|null   $limit Data limit.
     *
     * @return array|null
     */
    public function select(array $columns, string $table, array $where = null,
        array $order_by = null, int $limit = null
    ) :? array {
        $query = 'SELECT ' . implode($columns, ', ') . ' FROM ' . $table;

        $exec_parameters = [];
        if (null !== $where) {
            $query .= ' WHERE';

            foreach ($where as $i) {
                $query .= " {$i['logic_operator']} {$i['column']} {$i['operator']} :{$i['column']}";
                $exec_parameters[":{$i['column']}"] = $i['value'];
            }
        }

        if (null !== $order_by) {
            $query .= ' ORDER BY';

            foreach ($order_by as $k => $v) {
                $query .= " {$k} {$v}, ";
            }

            $query = substr($query, 0, -2);
        }

        if (null !== $limit) {
            $query .= ' LIMIT ' . $limit;
        }

        $query .= ';';

        $data = null;
        try {
            $sth = $this->_connection
                ->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

            $sth->execute($exec_parameters);

            $data = $sth->fetchAll();
        } catch (PDOException $e) {
            echo $e->getCode() . ': ' . $e->getMessage();
        }

        return $data;
    }

    /**
     * DataManager destructor for closing connection.
     */
    public function __destruct()
    {
        $this->_connection = null;
    }
}