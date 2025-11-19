<?php
include './config.php';

// Connect to the database
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novaccess</title>
</head>
<body>
    <h1>Bienvenue sur Novaccess</h1>

    <ul>
        <?php
        // Fetch all links from the database
        $stmt = $pdo->query("SELECT id, url, name, description, needs_auth, created_at FROM links");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo 
            "<li>
                <a href='" . htmlspecialchars($row['url']) . "' target='_blank'>" .
                    htmlspecialchars($row['name']) . 
                "</a>" 
                . " : " . htmlspecialchars($row['description']) . 
            "</li>";
        }
        ?>
    </ul>
</body>
</html>