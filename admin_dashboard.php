<?php
/*
ADMIN_DASHBOARD.php
Purpose: Dashboard page for Admin users
Features: 
- Session validation
- Role validation
- Session timeout security
- Display last login
- Auto-updating current date & time (JavaScript refresh every second)
*/
 
session_start();
include("includes/config.php");

// Session Validation

// Check if user is logged in 
if(!isset($_SESSION['user_id'])) {
    	header("Location: login.php");
    	exit();
}

// Check if user is admin 
if($_SESSION['role'] != 'admin') {
    	header("Location: login.php");
    	exit();
}

// Session Timeout 

// Auto logout after 10 minutes of inactivity
if(isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 600)) {
    	session_unset();
    	session_destroy();
    	header("Location: login.php");
    	exit();
}

// Update last activity time
$_SESSION['last_activity'] = time();

// Fetch Last Login
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT last_login FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    	<title>Admin Dashboard</title>
    	<script>
        	// Auto-update current time every second
        	function updateTime() {
            		const now = new Date();
            		document.getElementById("currentTime").innerHTML = 
                		now.getFullYear() + "-" +
                		String(now.getMonth()+1).padStart(2,'0') + "-" +
                		String(now.getDate()).padStart(2,'0') + " " +
                		String(now.getHours()).padStart(2,'0') + ":" +
                		String(now.getMinutes()).padStart(2,'0') + ":" +
                		String(now.getSeconds()).padStart(2,'0');
        	}
        	setInterval(updateTime, 1000);
        	window.onload = updateTime;
    	</script>
</head>
<body>
    	<h1>Admin Dashboard</h1>
    
    	<!-- Welcome Message -->
    	<p>Welcome, <?php echo $_SESSION['full_name']; ?>!</p>

    	<!-- Role Display -->
    	<p><strong>Role: </strong> <?php echo strtoupper($_SESSION['role']); ?></p>

    	<!-- Last Login Display -->
    	<p><strong>Last Login: </strong>
        	<?php echo $user['last_login'] ? $user['last_login'] : "First Login"; ?>
    	</p>

    	<!-- Current Date & Time -->
    	<p><strong>Current Date & Time: </strong> <span id="currentTime"></span></p>
    	<hr>
    
    	<!-- Navigation Menu -->
    	<h3>Navigation</h3>
    	<ul>
        	<li><a href="manage_students.php">Manage Students</a></li>
        	<li><a href="manage_internships.php">Manage Internships</a></li>
        	<li><a href="register_user.php">Register New User (Admin Only)</a></li>
        	<li><a href="logout.php">Logout</a></li>
    	</ul>
</body>
</html>
