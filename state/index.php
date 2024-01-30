<?php
require_once 'config.php';

// Set the content type to JSON
header('Content-Type: application/json');

// Handle HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Extract the requested state from the URL
        $requestUri = $_SERVER['REQUEST_URI'];
        $parts = explode('/', $requestUri);
        $requestedstate = end($parts);

        if (!empty($requestedstate)) {
            // Read operation (fetch countries based on search)
            $stmt = $pdo->prepare('SELECT id, country, state, stateHindi, country_id FROM state WHERE state LIKE :search');
            $stmt->bindValue(':search', '%' . $requestedstate . '%', PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Modify the response to include Hindi names
            $response = array_map(function ($row) {
                return [
                    'id' => $row['id'],
                    'state' => $row['state'],
                    'stateHindi' => $row['stateHindi'],
                    'countryHindi' => $row['countryHindi'],
                    'country' => $row['country'],
                    'country_id' => $row['country_id'],
                ];
            }, $result);

            // Encode JSON with JSON_UNESCAPED_UNICODE flag
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        } else {
            // If no state name provided, return all countries
            $stmt = $pdo->query('SELECT id, country, countryHindi, state, stateHindi, country_id FROM state');
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Modify the response to include Hindi names
            $response = array_map(function ($row) {
                return [
                    'id' => $row['id'],
                    'country' => $row['country'],
                    'countryHindi' => $row['countryHindi'],
                    'state' => $row['state'],
                    'stateHindi' => $row['stateHindi'],
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
