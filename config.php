<?php
class Config
{
    private static $pdo = null;

    public static function getConnexion()
    {
        if (self::$pdo === null) {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "Esprit-PIDEV-2A-2026-DigitEra";

            try {
                self::$pdo = new PDO(
                    "mysql:host=$servername;dbname=$dbname;charset=utf8",
                    $username,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );

              

            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}
?>