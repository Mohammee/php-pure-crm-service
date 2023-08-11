<?php

$pdo = new PDO("sqlite::memory:");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = <<< EOS
CREATE  TABLE users (
  id INTEGER PRIMARY KEY,
  username CHAR NOT NULL,
  password CHAR NOT NULL
);
INSERT INTO users (username, password) VALUES ('foo', 'soh7uido');
INSERT INTO users (username, password) VALUES ('bar', 'sha3ma5u');
 
EOS;
$pdo->exec($sql);

class User {
    protected $id;
    protected $username;
    protected $password;

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }
}

echo "-----------------------------------------------------------------\n";
echo "case 1: use PDO::FETCH_CLASS, works:\n";

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = 'foo'");
$stmt->execute();

$stmt->setFetchMode(PDO::FETCH_CLASS, 'User');

// works well
$user = $stmt->fetch();
echo "User: {$user->getUsername()} loaded, id: {$user->getId()}\n";

echo "-----------------------------------------------------------------\n";
echo "case 1: use PDO::FETCH_INTO, doesn't work, throws fatal error:\n";

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = 'foo'");
$stmt->execute();

$user = new User();
$stmt->setFetchMode(PDO::FETCH_INTO, $user);

$user = $stmt->fetch();