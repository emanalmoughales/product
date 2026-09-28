<!-- الاتصال يقاعدة البيانات -->
<?php
$db_name = "products";
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$dsn = "mysql:host=$db_host;dbname=$db_name";
try {
    $pdo = new PDO($dsn, $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "counldn't connect because" . $e->getMessage();
};
