<?php
/*
MANAGE_ASSESSMENTS.php
Purpose: Assessors record evaluations for students
Features:
- Session & role validation (assessor only)
- Submit new assessment (score + comments)
- Display submitted assessments
*/

session_start();
include("includes/config.php");

// Ensure only Assessors can access
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'assessor') {
	header("Location: login.php");
	exit();
}

$message = "";

// Handle Assessment Submission
if(isset($_POST['submit_assessment'])) {
	$student_id   = $_POST['student_id'];
	$assessor_id  = $_SESSION['user_id'];
    	$score        = $_POST['score'];
    	$comments     = trim($_POST['comments']);

    	// Basic validation
    	if(!is_numeric($score) || $score < 0 || $score > 100) {
        	$message = "Error: Score must be between 0 and 100.";
    	} else {
        	$stmt = $conn->prepare("SELECT internship_id FROM internships WHERE student_id = ? AND assessor_id = ? LIMIT 1");
		$stmt->bind_param("ii", $student_id, $assessor_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$internship = $result->fetch_assoc();
		$stmt->close();

		if($internship) {
			$internship_id = $internship['internship_id'];
			$stmt = $conn->prepare("INSERT INTO assessments (internship_id, assessor_id, total_score, comments, created_at) VALUES (?, ?, ?, ?, NOW())");
			$stmt->bind_param("iiis", $internship_id, $assessor_id, $score, $comments);

			if($stmt->execute()) {
				$message = "Assessment submitted successfully!";
			} else {
				$message = "Error: Could not submit assessment.";
			}
			$stmt->close();
		} else {
			$message = "Error: Internship not found for this student.";
		}
    	}
}

// Fetch students assigned to this assessor
$sql = "SELECT i.internship_id, s.student_id, s.student_name, s.matric_no
	FROM internships i
        JOIN students s ON i.student_id = s.student_id
        WHERE i.assessor_id = ?
        ORDER BY s.student_name ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$students = $stmt->get_result();
$stmt->close();

// Fetch submitted assessments
$sql2 = "SELECT a.assessment_id, s.student_name, s.matric_no, a.total_score, a.comments, a.created_at
        FROM assessments a
	JOIN internships i ON a.internship_id = i.internship_id
        JOIN students s ON i.student_id = s.student_id
        WHERE a.assessor_id = ?
        ORDER BY a.created_at DESC";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $_SESSION['user_id']);
$stmt2->execute();
$assessments = $stmt2->get_result();
$stmt2->close();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Manage Assessments</title>
</head>
<body>
    	<h1>Manage Assessments</h1>
    	<a href="assessor_dashboard.php">Back to Dashboard</a>
    	<hr>

    	<!-- Feedback message -->
    	<?php if($message != "") echo "<p style='color:red'>$message</p>"; ?>

    	<!-- Assessment Form -->
    	<h3>Submit New Assessment</h3>
    	<form method="POST">
        	Student:
        	<select name="student_id" required>
            		<option value="">-- Select Student --</option>
            		<?php while($s = $students->fetch_assoc()) { ?>
                		<option value="<?php echo $s['student_id']; ?>">
                    			<?php echo htmlspecialchars($s['student_name'])." (".$s['matric_no'].")"; ?>
                		</option>
            		<?php } ?>
        	</select><br><br>

        	Score (0–100): <input type="number" name="score" min="0" max="100" required><br><br>
        	Comments:<br>
        	<textarea name="comments" rows="4" cols="50"></textarea><br><br>

        	<button type="submit" name="submit_assessment">Submit Assessment</button>
    	</form>
    	<hr>

    	<!-- Assessment Records -->
    	<h3>Submitted Assessments</h3>
    	<table border="1" cellpadding="5">
        	<tr>
            		<th>ID</th><th>Student</th><th>Matric No</th><th>Score</th><th>Comments</th><th>Date</th>
        	</tr>
        	<?php while($row = $assessments->fetch_assoc()) { ?>
        	<tr>
            		<td><?php echo htmlspecialchars($row['assessment_id']); ?></td>
            		<td><?php echo htmlspecialchars($row['student_name']); ?></td>
            		<td><?php echo htmlspecialchars($row['matric_no']); ?></td>
            		<td><?php echo htmlspecialchars($row['total_score']); ?></td>
            		<td><?php echo htmlspecialchars($row['comments']); ?></td>
            		<td><?php echo htmlspecialchars($row['created_at']); ?></td>
        	</tr>
        	<?php } ?>
    	</table>
</body>
</html>

