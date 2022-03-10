<?php namespace Core;

final class DatabaseService
{
    private string $server;
    private string $username;
    private ?string $password;

    private \PDO $database;

    public final function withServer(string $host, string $databaseName): DatabaseService
    {
        $this->server = "mysql:host=$host;dbname=$databaseName";
        return $this;
    }

    public final function withUsername(string $username): DatabaseService
    {
        $this->username = $username;
        return $this;
    }

    public final function withPassword(?string $password = null): DatabaseService
    {
        $this->password = $password;
        return $this;
    }

    public final function run(): void
    {
        $this->database = new \PDO($this->server, $this->username, $this->password);
        return;
    }

    public final function get(): \PDO
    {
        return $this->database;
    }
}

?>