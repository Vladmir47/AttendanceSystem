<?php
// Include database connection
include 'db.php';

// Check if the 'id' parameter exists in the URL
if (isset($_GET['id'])) {
    $student_id = intval($_GET['id']); // Get the student ID and ensure it's an integer

    // Prepare the DELETE query
    $delete_query = $conn->prepare("DELETE FROM attendance WHERE student_id = ?");
    $delete_query->bind_param("i", $student_id);

    // Execute the query
    if ($delete_query->execute()) {
        // Redirect back to the students list with a success message
        header("Location: view_attendance.php?msg=Attendance+record+deleted+successfully");
    } else {
        // Redirect back with an error message
        header("Location: view_attendance.php?msg=Error+deleting+record");
    }

    // Close the statement
    $delete_query->close();
} else {
    // Redirect back if no ID is provided
    header("Location: view_attendance.php?msg=Invalid+student+ID");
}

// Close the database connection
$conn->close();
