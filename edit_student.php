<?php
// Include database connection
include 'db.php';

// Check if the 'id' parameter exists in the URL
if (isset($_GET['id'])) {
    $student_id = intval($_GET['id']); // Get the student ID and ensure it's an integer

    // Fetch the student details
    $query = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $query->bind_param("i", $student_id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc(); // Fetch the student's data
    } else {
        die("Student not found!");
    }
} else {
    die("Invalid student ID!");
}

// Handle form submission for updating student details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $course = $_POST['course'];
    $reg_number = $_POST['registration_number'];

    // Update the student details in the database
    $update_query = $conn->prepare("UPDATE students SET name = ?, course = ?, registration_number = ? WHERE id = ?");
    $update_query->bind_param("sssi", $name,  $course, $reg_number, $student_id);

    if ($update_query->execute()) {
        // Redirect to the view_students.php page with a success message
        header("Location: view_students.php?msg=Student+updated+successfully");
        exit;
    } else {
        $error = "Error updating student information!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Student Information</h1>

        <?php if (isset($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="name">Student Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="course">Course:</label>
                <input type="text" id="course" name="course" value="<?php echo htmlspecialchars($student['course']); ?>" required>
            </div>
            <div class="form-group">
                <label for="registration number">Reg NO:</label>
                <input type="text" id="registration_number" name="registration_number" value="<?php echo htmlspecialchars($student['registration_number']); ?>" required>
            </div>
            <button type="submit" class="btn">Update Student</button>
        </form>
        <a href="view_students.php" class="btn btn-secondary">Back to Students</a>
    </div>
</body>
</html>
