<?php
header('Content-Type: application/json');

// Database connection parameters
$host = 'localhost';
$dbname = 'inventory';
$username = 'root'; // Default XAMPP MySQL username
$password = ''; // Default XAMPP MySQL password

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the POST data
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (isset($data['items']) && is_array($data['items'])) {
        // Prepare SQL statement
        $stmt = $pdo->prepare("INSERT INTO items (item_name, quantity, price, total) VALUES (:item_name, :quantity, :price, :total)");

        // Insert each item into the database
        foreach ($data['items'] as $item) {
            $stmt->execute([
                ':item_name' => $item['itemName'],
                ':quantity' => $item['quantity'],
                ':price' => $item['price'],
                ':total' => $item['total']
            ]);
        }

        // Return success response
        echo json_encode(['success' => true, 'message' => 'Items saved successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid data format.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
