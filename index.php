<?php
header('Content-Type: application/json');

$token = $_SERVER['HTTP_X_DEVICE_TOKEN'] ?? '';

if (!hash_equals('ujicobasounboxqris123', $token)) {
    http_response_code(401);
    echo json_encode(['error' => 'unauthorized']);
    exit;
}

$file = __DIR__ . '/queue.json';

$queue = file_exists($file)
    ? json_decode(file_get_contents($file), true)
    : [];

$queue = is_array($queue) ? $queue : [];

foreach ($queue as $payment) {
    if (($payment['status'] ?? '') === 'pending') {
        echo json_encode([
            'payment' => [
                'id'     => $payment['id'],
                'amount' => (int) $payment['amount']
            ]
        ]);
        exit;
    }
}

echo json_encode(['payment' => null]);
