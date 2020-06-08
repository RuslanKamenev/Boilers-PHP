<?php

class DB {

    private static string $user = 'root';
    private static string  $pass = '';
    private static string  $baseName = 'boilers';

    public static object $pdo;

    function __construct() {
    }

    public static function setConnection () {
        self::$pdo = new PDO('mysql:host=localhost;dbname='.self::$baseName, self::$user, self::$pass, [\PDO::ATTR_DEFAULT_FETCH_MODE 	=> \PDO::FETCH_ASSOC]);
    }
}
