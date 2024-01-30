<?php
// Step 1: Connect to the database
$host = 'localhost';
$dbname = 'world';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Step 2: Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Step 3: Extract requested resource and search term from the URL
    $requestUri = $_SERVER['REQUEST_URI'];
    $parts = explode('/', $requestUri);
    $resource = $parts[count($parts) - 2];
    $searchTerm = end($parts);

    // Step 4: Query the database based on the requested resource and search term
    switch ($resource) {
        case 'country':
            // Search for countries
            $stmt = $pdo->prepare('SELECT * FROM country WHERE country LIKE :searchTerm');
            $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
            break;
        case 'state':
            // Search for states
            $stmt = $pdo->prepare('SELECT * FROM state WHERE state LIKE :searchTerm');
            $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
            break;
        case 'city':
            // Search for cities
            $stmt = $pdo->prepare('SELECT * FROM city WHERE city LIKE :searchTerm');
            $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
            break;
        default:
            // If the requested resource is not recognized
            http_response_code(404);
            exit();
    }

    // Execute the query
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Step 5: Format the response as JSON
    header('Content-Type: application/json');
    echo json_encode($data);
}
?>
