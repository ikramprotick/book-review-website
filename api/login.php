<?php
session_start();
header('Content-Type: application/json');

$in = json_decode(file_get_contents('php://input'), true);
if (!isset($in['username'], $in['password'])) {
  http_response_code(400);
  echo json_encode(['error'=>'Username and password required']);
  exit;
}

$username = $in['username'];
$password = $in['password'];

$usersFile = __DIR__ . '/../data/users.json';
if (!file_exists($usersFile)) {
  http_response_code(401);
  echo json_encode(['error'=>'Invalid credentials']);
  exit;
}

$users = json_decode(file_get_contents($usersFile), true);
foreach ($users as $u) {
  if (strcasecmp($u['username'], $username) === 0 &&
      password_verify($password, $u['hash'])) {
    // Successful login
    $_SESSION['userId'] = $u['id'];
    echo json_encode(['user'=>['id'=>$u['id'],'username'=>$u['username']]]);
    exit;
  }
}

// If we reach here, no match
http_response_code(401);
echo json_encode(['error'=>'Invalid credentials']);
