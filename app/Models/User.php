<?php

namespace App\Models;

class User extends Model
{
    public $id;
    public $name;
    public $email;
    public $status;
    public $password;
    public $role;

    public function getUsers()
    {
        $stmt = $this->pdo->query('select * from users where role != "admin"');
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_CLASS, User::class);
    }

    public function findByEmail($email)
    {
        $stmt = $this->pdo->prepare('select * from users where email = ?');
        $stmt->bindValue(1,$email);
        $stmt->execute();

        $stmt->setFetchMode(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE, self::class);

        return $stmt->fetch();
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare('delete from users where id = :id');

        $stmt->bindValue('id', $id);
       return $stmt->execute();
    }

    public function updateStatus($status)
    {
        $stmt = $this->pdo->prepare('update users set status = ?');
        $stmt->bindValue(1, $status);
        return $stmt->execute();
    }
}