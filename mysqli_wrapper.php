<?php

class mysqli_wrapper {

    private $connection;

    public function __construct($host, $username, $password, $db_name, $port) {
        $this->connection = new \mysqli($host, $username, $password, $db_name, $port); //create mysqli object

        if (!$this->connection)
        {
            throw new \Exception('Error connecting to DB'); 
        }
    }

    public function __destruct() {
        $this->connection->close(); //close connection on destructor
    }

    public function query(string $query, array $args = [], string $types = null) {
        if ($types === null && $args !== []) //if we have arguments and no types are specified we act as if they were all strings
        {
            $types = str_repeat('s', count($args));
        }

        $stmt = $this->connection->prepare($query); //prepare the query

        if (!$stmt)
        {
            throw new \Exception('Error preparing query');
        }
        
        if (strpos($query, '?') !== false)
        {
            $stmt->bind_param($types, ...$args); //bind our parameters to the query
        }
        
        $stmt->execute(); //execute our query

        $result = $stmt->get_result(); //get query result

        $stmt->close(); //close db handle

        return $result; //return result
    }

    public function get_connection(): mysqli {
        return $this->connection;
    }

}