<?php
/*
FETCH_NOTIFICATIONS.php
Purpose: Fetch unread notifications for the logged-in assessor
Features:
- Session validation
- Retrieve unread notifications
- Mark notifications as read after fetching
- Return JSON response
*/

session_start();
include("includes/config.php");

// Ensure assessor is logged in
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'assessor') {
    	http_response_code(403);
    	echo json_encode(["error" => "Unauthorized"]);
    	exit();
}

$assessor_id = $_SESSION['user_id'];

// Fetch unread notifications
$stmt = $conn->prepare("SELECT id, message, created_at FROM notifications WHERE assessor_id = ? AND is_read = 0 ORDER BY created_at DESC");
$stmt->bind_param("i", $assessor_id);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    	$notifications[] = $row;

    	// Mark as read immediately
    	$update = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ?");
   	$update->bind_param("i", $row['id']);
    	$update->execute();
}

echo json_encode($notifications);
?>
