<?php
// Mock data for demonstration (replace this with database query results if needed)
$orders = [
    ['id' => 1, 'name' => 'Order 1', 'status' => 'Shipped'],
    ['id' => 2, 'name' => 'Order 2', 'status' => 'Processing'],
    ['id' => 3, 'name' => 'Order 3', 'status' => 'Delivered'],
];

// Generate HTML for the orders list
$output = '';
foreach ($orders as $order) {
    $output .= '<li>';
    $output .= 'Order Name: ' . htmlspecialchars($order['name']) . '<br>';
    $output .= 'Status: ' . htmlspecialchars($order['status']);
    $output .= '</li>';
}

// Output the generated HTML
echo $output;
?>