<?php
header('Content-Type: application/json');

// Read the raw input JSON from Fetch
$input = json_decode(file_get_contents('php://input'), true);

// Validate that inputs exist
if (!isset($input['snack'], $input['price'], $input['quantity'], $input['cash'])) {
    echo json_encode(['success' => false, 'error' => 'Missing input fields.']);
    exit;
}

$snack = trim($input['snack']);
$price = floatval($input['price']);
$quantity = intval($input['quantity']);
$cash = floatval($input['cash']);

// Check for empty or invalid fields
if ($snack === '' || $price <= 0 || $quantity <= 0 || $cash <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid or empty input fields.']);
    exit;
}

$total = $price * $quantity;

// Check if cash is enough
if ($cash < $total) {
    $short = $total - $cash;
    echo json_encode([
        'success' => false,
        'error' => "Insufficient cash. You need ₱" . number_format($short, 2)
    ]);
    exit;
}

// Calculate change
$change = $cash - $total;

// Return success JSON
echo json_encode([
    'success' => true,
    'message' => "You successfully bought {$quantity} pack(s) of {$snack}.",
    'total' => number_format($total, 2),
    'change' => number_format($change, 2)
]);
?>
