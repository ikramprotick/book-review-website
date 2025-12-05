<?php
session_start();
header('Content-Type: application/json');

// Read JSON input
$in = json_decode(file_get_contents('php://input'), true);
if (!isset($in['username'], $in['password'])) {
  http_response_code(400);
  echo json_encode(['error'=>'Username and password required']);
  exit;
}

$username = trim($in['username']);
$password = $in['password'];
if ($username === '' || $password === '') {
  http_response_code(400);
  echo json_encode(['error'=>'Invalid input']);
  exit;
}

$usersFile = __DIR__ . '/../data/users.json';
// Ensure users file exists
if (!file_exists($usersFile)) {
  file_put_contents($usersFile, json_encode([], JSON_PRETTY_PRINT));
}
$users = json_decode(file_get_contents($usersFile), true);

// Check for duplicate username
foreach ($users as $u) {
  if (strcasecmp($u['username'], $username) === 0) {
    http_response_code(409);
    echo json_encode(['error'=>'Username already taken']);
    exit;
  }
}

// Create new user
$newId = time();  // or use a better ID generator
$newUser = [
  'id'       => $newId,
  'username' => $username,
  // Store a password hash, not plaintext!
  'hash'     => password_hash($password, PASSWORD_DEFAULT)
];

$users[] = $newUser;
file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));

// Set session and return user info
$_SESSION['userId'] = $newId;
echo json_encode(['user'=>['id'=>$newId,'username'=>$username]]);
