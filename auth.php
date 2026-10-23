    <?php
// Handles admin login and logout via POST requests
session_start();
header('Content-Type: application/json');

// ====== EDIT ADMIN CREDENTIALS HERE ======
$ADMIN_USER = 'admin';
$ADMIN_PASS = 'admin123';

$action = $_POST['action'] ?? '';

if ($action === 'logout') {
  session_unset();
  session_destroy();
  echo json_encode(['ok' => true, 'message' => 'Logged out successfully.']);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['ok' => false, 'message' => 'Method not allowed.']);
  exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === $ADMIN_USER && $password === $ADMIN_PASS) {
  $_SESSION['admin'] = $username;
  echo json_encode(['ok' => true, 'message' => 'Login successful! Reloading...']);
} else {
  http_response_code(401);
  echo json_encode(['ok' => false, 'message' => 'Invalid username or password.']);
}