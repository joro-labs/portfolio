<?php
// Receives the contact form (POST), validates it on the server, saves to data/messages.txt
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['ok' => false, 'message' => 'Use the contact form to send a message.']);
  exit;
}

$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if (strlen($name) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($message) < 10 || strlen($message) > 500) {
  http_response_code(422);
  echo json_encode(['ok' => false, 'message' => 'Please check your name, email and message.']);
  exit;
}

$line = json_encode(['time' => date('c'), 'name' => $name, 'email' => $email, 'message' => $message]) . PHP_EOL;
$saved = file_put_contents(__DIR__ . '/data/messages.txt', $line, FILE_APPEND | LOCK_EX);

echo json_encode($saved === false
  ? ['ok' => false, 'message' => 'Could not save your message. Try again later.']
  : ['ok' => true,  'message' => 'Message sent. Thank you, ' . htmlspecialchars($name) . '!']);
