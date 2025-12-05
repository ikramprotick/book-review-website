<?php
session_start();
header('Content-Type: application/json');

// Path to your reviews data file
$dataFile = __DIR__ . '/../data/reviews.json';

// Ensure the file exists
if (!file_exists($dataFile)) {
    file_put_contents($dataFile, json_encode([], JSON_PRETTY_PRINT));
}

// Load existing reviews
$reviews = json_decode(file_get_contents($dataFile), true) ?: [];

/**
 * Persist reviews array back to disk.
 *
 * @param array $data
 * @return void
 */
function save_reviews(array $data): void {
    global $dataFile;
    file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT));
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Return all reviews
        echo json_encode($reviews);
        break;

    case 'POST':
        // Create a new review — only for authenticated users
        if (!isset($_SESSION['userId'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        $input = json_decode(file_get_contents('php://input'), true);
        $new = array_merge([
            'id'       => time(),
            'likes'    => 0,
            'dislikes' => 0,
            'authorId' => $_SESSION['userId'],
        ], array_intersect_key($input, array_flip(['title','content','category','rating','cover'])));
        $reviews[] = $new;
        save_reviews($reviews);
        echo json_encode($new);
        break;

    case 'PUT':
        // Update likes/dislikes or edit content (only owner can edit content)
        if (!isset($_SESSION['userId'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        $input = json_decode(file_get_contents('php://input'), true);
        foreach ($reviews as &$r) {
            if ($r['id'] == $input['id']) {
                // Always allow like/dislike
                if (isset($input['likes']))    $r['likes']    = (int)$input['likes'];
                if (isset($input['dislikes'])) $r['dislikes'] = (int)$input['dislikes'];
                // Only author can edit review fields
                if ($r['authorId'] === $_SESSION['userId']) {
                    foreach (['title','content','category','rating','cover'] as $field) {
                        if (isset($input[$field])) {
                            $r[$field] = $input[$field];
                        }
                    }
                }
                break;
            }
        }
        save_reviews($reviews);
        echo json_encode(['success' => true]);
        break;

    case 'DELETE':
        // Delete a review — only owner can delete
        if (!isset($_SESSION['userId'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        $input = json_decode(file_get_contents('php://input'), true);
        $deleted = false;
        foreach ($reviews as $idx => $r) {
            if ($r['id'] == $input['id'] && $r['authorId'] === $_SESSION['userId']) {
                array_splice($reviews, $idx, 1);
                $deleted = true;
                break;
            }
        }
        if ($deleted) {
            save_reviews($reviews);
            echo json_encode(['success' => true]);
        } else {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
}
