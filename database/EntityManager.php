<?php

namespace project\database;

class EntityManager
{
    private $connection;
    private string $server;
    private string $user;
    private string $pass;
    private string $dbname;

    public function __construct(string $envFile)
    {
        // Загрузка данных из файла конфигурации и инициализация параметров соединения
        $config = parse_ini_file($envFile);

        $this->server = $config['server'] ?? '';
        $this->user = $config['user'] ?? '';
        $this->pass = $config['pass'] ?? '';
        $this->dbname = $config['dbname'] ?? '';

        //$this->openConnection();
    }


    public function close(): void
    {
        // Закрытие соединения с базой данных
    }
}
