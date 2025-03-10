<?php
namespace App\Model;

use Doctrine\DBAL\Connection;

class UserModel
{
    public function __construct(private Connection $conn)
    {
    }

    public function getAll(): array
    {
        return $this->conn
            ->executeQuery('SELECT * FROM ant_customers')
            ->fetchAllAssociative();
    }
}
