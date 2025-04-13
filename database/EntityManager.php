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

    public function find(string $table, string $id): void
    {
        // Поиск записи в таблице $table с заданным идентификатором $id
    }

    public function insert(string $table, array $data): void
    {
        // Вставка новой записи в таблицу $table с переданными данными $data
    }

    public function update(string $table, string $id, array $data): void
    {
        // Обновление записи в таблице $table с идентификатором $id новыми данными $data
    }

    public function delete(string $table, string $id): void
    {
        // Удаление записи из таблицы $table с указанным идентификатором $id
    }

    public function close(): void
    {
        // Закрытие соединения с базой данных
    }
}
