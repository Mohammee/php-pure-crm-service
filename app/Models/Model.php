<?php

namespace App\Models;

use App\DB;

class Model implements \ArrayAccess
{

    protected DB $pdo;
    public function __construct()
    {
        $this->pdo = DB::getInstace();
    }


    public function offsetExists($offset) {
        return property_exists($this, $offset);
    }

    public function offsetGet($offset) {
        return $this->{$offset};
    }

    public function offsetSet($offset, $value) {
        $this->{$offset} = $value;
    }

    public function offsetUnset($offset) {
        $this->{$offset} = null;
    }

    public function __get(string $name)
    {
        if(property_exists($this, $name)){
            return $this->{$name};
        }

        return null;
    }

  public function __set(string $name, $value): void
  {
      if(property_exists($this, $name)){
           $this->{$name} = $value;
      }
  }


    public function findById($id)
    {
        $stmt = $this->pdo->prepare('select * from users where id = ?');
        $stmt->bindValue(1,$id);
        $stmt->execute();

        $stmt->setFetchMode(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE, static::class);

        return $stmt->fetch();
    }
}