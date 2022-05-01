<?php

class Database
{

    private static $INSTANCE = null;
    private $mysqli,

    $HOST = "YOUR_HOST",
    $USER = "YOUR_USER",
    $PASS = "YOUR_PASSWORD",
    $DATABASE = "YOUR_DATABASE",
    $PORT = "YOUR_PORT";

    public function __construct()
    {

        $this->mysqli = new mysqli($this->HOST, $this->USER, $this->PASS, $this->DATABASE, $this->PORT);

        if (mysqli_connect_error()) {
            die("Koneksi gagal terhubung ke Database");
        }

    }

    // Singleton pattern, Menguji koneksi agar tidak double
    public static function getInstance()
    {
        if (!isset(self::$INSTANCE)) {
            self::$INSTANCE = new Database();
        }

        return self::$INSTANCE;
    }

    public function insert($table, $fields = array())
    {

        // Get Column
        $column = implode(", ", array_keys($fields));

        // Get Values
        $valueArrays = array();
        $i = 0;
        foreach ($fields as $key => $values) {

            $valueArrays[$i] = "'" . $values . "'";

            $i++;
        }

        $values = implode(", ", $valueArrays);

        $query = "INSERT INTO $table ($column) VALUES ($values)";

        return $this->run_query($query, "Masalah saat memasukan data");
    }


    public function get_info($table, $column, $value)
    {
        if (!is_int($value)) {
            $value = "'" . $value . "'";
        }

        $query = "SELECT * FROM $table WHERE $column = $value";
        $result = $this->mysqli->query($query);

        while ($row = $result->fetch_assoc()) {
            return $row;
        }


    }

    public function run_query($query, $message)
    {
        if ($this->mysqli->query($query))
            return true;
        else
            echo($message);
    }

}