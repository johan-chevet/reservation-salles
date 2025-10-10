<?php

namespace Src\Models;

use Core\Model;
use Core\Database;

class User extends Model
{

    // If table name is not equal to the class name + s, define it below
    // protected static string $table_name = "users";

    public function __construct()
    {
        parent::__construct();
    }

    public string $login;
    public string $password;

    public static function find_by_login(string $login): User | false
    {
        $sql = "SELECT * FROM " . static::get_table_name() . " WHERE login = ?";
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute([$login]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, __CLASS__);
        return $stmt->fetch();
    }
}
