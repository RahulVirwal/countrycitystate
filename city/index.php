<?php
require_once 'config.php';

// Set the content type to JSON
header('Content-Type: application/json');

// Handle HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Extract the requested city from the URL
        $requestUri = $_SERVER['REQUEST_URI'];
        $parts = explode('/', $requestUri);
        $requestedcity = end($parts);

        if (!empty($requestedcity)) {
            // Read operation (fetch countries based on search)
            $stmt = $pdo->prepare('SELECT * FROM city WHERE city LIKE :search');
            $stmt->bindValue(':search', '%' . $requestedcity . '%', PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Modify the response to include Hindi names
            $response = array_map(function ($row) {
                return [
                    'id' => $row['id'],
                    'city' => $row['city'],
                    'cityHindi' => $row['cityHindi'],
                    'state_id' => $row['state_id'],
                    'state' => $row['state'],
                    'stateHindi' => $row['stateHindi'],
                    'country' => $row['country'],
                    'countryHindi' => $row['countryHindi'],
                    'country_id' => $row['country_id'],
                ];
            }, $result);

            // Encode JSON with JSON_UNESCAPED_UNICODE flag
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        } else {
            // If no city name provided, return all countries
            $stmt = $pdo->query('SELECT * FROM city');
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Modify the response to include Hindi names
            $response = array_map(function ($row) {
                return [
                    'id' => $row['id'],
                    'city' => $row['city'],
                    'cityHindi' => $row['cityHindi'],
                    'country' => $row['country'],
                    'state_id' => $row['state_id'],
                    'state' => $row['state'],
                    'stateHindi' => $row['stateHindi'],
                    'countryHindi' => $row['countryHindi'],
                    'country_id' => $row['country_id'],
                ];
            }, $result);

            // Encode JSON with JSON_UNESCAPED_UNICODE flag
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
        break;
    // ... (other cases remain the same)
}
?>
