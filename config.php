<?php

class Config {
    private const DBHOST = 'localhost';
    private const DBNAME = 'fetch_crud_app';

    private $config;
    private $dsn = 'mysql:host=' . self::DBHOST . ';dbname=' . self::DBNAME . '';

    protected $conn = null;

    public function __construct() {
        try {
            $this->config = parse_ini_file("config.ini");
            $this->conn = new PDO($this->dsn, $this->config['DBUSER'], $this->config['DBPASS']);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Ошибка: ' . $e->getMessage());
        }
    }
}

?>