<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iotphase3";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
    exit;
}

$data = json_decode($_POST['dates'], true);
$startDate = $data['start'];
$endDate = $data['end'];
$sales = [];

$products_sql = "SELECT * FROM products";
$products_stmt = $conn->prepare($products_sql);
$products_stmt->execute();
$products_result = $products_stmt->get_result();
$products = $products_result->fetch_all();

$productsNumber = $products_result->num_rows;


for ($i = 0; $i < $productsNumber; $i++) {
    $product_sold_sql = "SELECT r.*, p.name, i.quantity, i.line_total FROM receipts r 
    JOIN receipt_items i 
    ON r.receipt_id = i.receipt_id
    JOIN products p
    ON i.product_id = p.product_id
    WHERE i.product_id = ?
    AND receipt_date 
    BETWEEN ? AND ?
    ";

    $sold_stmt = $conn->prepare($product_sold_sql);
    $sold_stmt->bind_param("iss", $products[$i][0], $startDate, $endDate);
    $sold_stmt->execute();
    $sold_result = $sold_stmt->get_result();
    $sold = $sold_result->fetch_all();
    
    $quantity = 0;
    $revenu = 0;

    for ($j = 0; $j < $sold_result->num_rows; $j++) {
        
        $quantity += $sold[$j][6];
        $revenu += $sold[$j][7];

        $sales[$products[$i][1]][] = $sold[$j];
        if ($j == $sold_result->num_rows - 1) {
            $sales[$products[$i][1]]['quantity'] = $quantity;
            $sales[$products[$i][1]]['revenu'] = $revenu;
        }
    }
}

echo json_encode(["sales" => $sales]);




