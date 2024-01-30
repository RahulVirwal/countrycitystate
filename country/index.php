<?php
require_once 'config.php';

// Set the content type to JSON
header('Content-Type: application/json');

// Handle HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Extract the requested country from the URL
        $requestUri = $_SERVER['REQUEST_URI'];
        $parts = explode('/', $requestUri);
        $requestedCountry = end($parts);

        if (!empty($requestedCountry)) {
            // Read operation (fetch countries based on search)
            $stmt = $pdo->prepare('SELECT id, iso2, country, countryHindi, phonecode FROM country WHERE country LIKE :search');
            $stmt->bindValue(':search', '%' . $requestedCountry . '%', PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Modify the response to include Hindi names
            $response = array_map(function ($row) {
                return [
                    'id' => $row['id'],
                    'iso2' => $row['iso2'],
                    'country' => $row['country'],
                    'countryHindi' => $row['countryHindi'],
                    'phonecode' => $row['phonecode'],
                ];
            }, $result);

            // Encode JSON with JSON_UNESCAPED_UNICODE flag
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        } else {
            // If no country name provided, return all countries
            $stmt = $pdo->query('SELECT id, iso2, country, countryHindi, phonecode FROM country');
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Modify the response to include Hindi names
            $response = array_map(function ($row) {
                return [
                    'id' => $row['id'],
                    'iso2' => $row['iso2'],
                    'country' => $row['country'],
                    'countryHindi' => $row['countryHindi'],
                    'phonecode' => $row['phonecode'],
                ];
            }, $result);

            // Encode JSON with JSON_UNESCAPED_UNICODE flag
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
        break;
    // ... (other cases remain the same)
}
?>
