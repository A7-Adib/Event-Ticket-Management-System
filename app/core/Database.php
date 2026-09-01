<?php
declare(strict_types=1);
class Database {
    private static ?mysqli $conn = null;
    public static function connection(): mysqli {
        if (self::$conn === null) {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            try { self::$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME); self::$conn->set_charset('utf8mb4'); }
            catch (mysqli_sql_exception $e) { http_response_code(500); exit('Database connection failed. Check XAMPP/MySQL and app/config/config.php.'); }
        }
        return self::$conn;
    }
}
?>