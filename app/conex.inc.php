<?php

if ($_SERVER['HTTP_HOST'] === 'localhost') {
    class Database
    {
        private static $host = 'localhost';
        private static $dbName = 'cnrd_nueva';
        private static $username = 'root';  // Usuario proporcionado
        private static $password = ''; // Añade aquí la contraseña correspondiente
        private static $conn = null;


        public static function connect()
        {
            if (self::$conn === null) {
                try {
                    self::$conn = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$dbName, self::$username, self::$password);
                    self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $e) {
                    die("Error al conectar a la base de datos: " . $e->getMessage());
                }
            }
            return self::$conn;
        }
    }
} elseif ($_SERVER['HTTP_HOST'] === 'cnrd-intranet.free.nf') {
    class Database
    {
        private static $host = 'sql103.infinityfree.com';
        private static $dbName = 'if0_37680311_cnrd';
        private static $username = 'if0_37680311';  // Usuario proporcionado
        private static $password = 'Ppw7oEivxNH'; // Añade aquí la contraseña correspondiente
        private static $conn = null;


        public static function connect()
        {
            if (self::$conn === null) {
                try {
                    self::$conn = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$dbName, self::$username, self::$password);
                    self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $e) {
                    die("Error al conectar a la base de datos: " . $e->getMessage());
                }
            }
            return self::$conn;
        }
    }
}
