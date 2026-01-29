

<?php
// Updated for Herald College Server
$host = "localhost";            
$db   = "np03cs4a240274";      
$user = "np03cs4a240274";      
$pass = "kBQQpxBKjU";          

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>