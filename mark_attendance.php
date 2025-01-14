<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Mark Attendance</title>
</head>
<body>
<nav>
    <a href="index.php">Home</a>
    <a href="create_class.php">Create class</a>
    <a href="add_student.php">Add Student</a>
    <a href="view_students.php">View Students</a>
    <a href="mark_attendance.php">Mark Attendance</a>
    <a href="view_attendance.php">View Attendance Reports</a>
</nav>
<header>
    <h1>Mark attendance</h1>
</header>
<div class="container">
    <h1>Mark Attendance</h1>

    <!-- Class Selection Form -->
    <form method="GET" action="">
        <label for="class_id">Select Class:</label>
        <select name="class_id" id="class_id" required>
            <option value="">Select a class</option>
            <?php
            // Fetch available classes
            $classes = $conn->query("SELECT * FROM classes");
            while ($class = $classes->fetch_assoc()) {
                echo "<option value='{$class['id']}'>{$class['name']}</option>";
            }
            ?>
        </select>
        <button type="submit" name="submit_class">Show Students</button>
    </form>

    <?php
    if (isset($_GET['submit_class'])) {
        $class_id = $_GET['class_id'];

        // Fetch students for the selected class
        $students = $conn->query("
            SELECT students.id, students.name 
            FROM students 
            JOIN student_classes ON students.id = student_classes.student_id 
            WHERE student_classes.class_id = '$class_id'
        ");

        if ($students->num_rows > 0) {
            echo "<form method='POST' action=''>";
            echo "<label for='date'>Date:</label>";
            echo "<input type='date' name='date' required><br><br>";

            echo "<table border='1'>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Status</th>
                    </tr>";

            // Initialize counter
            $counter = 1;

            // Loop through students and display attendance options
            while ($student = $students->fetch_assoc()) {
                echo "<tr>
                        <td>{$counter}</td>
                        <td>{$student['name']}</td>
                        <td>
                            <select name='status[{$student['id']}]'>
                                <option value='Present'>Present</option>
                                <option value='Absent'>Absent</option>
                                <option value='Late'>Late</option>
                            </select>
                        </td>
                    </tr>";
                    $counter++;
            }

            echo "</table>";
            echo "<input type='hidden' name='class_id' value='$class_id'>";
            echo "<button type='submit' name='submit_attendance'>Submit Attendance</button>";
            echo "</form>";
        } else {
            echo "<p>No students found in the selected class.</p>";
        }
    }

    // Mark attendance on form submission
if (isset($_POST['submit_attendance'])) {
    $date = $_POST['date'];
    $class_id = $_POST['class_id'];

    foreach ($_POST['status'] as $student_id => $status) {
        // Check if an attendance record already exists for the student, class, and date
        $check_sql = "SELECT * FROM attendance WHERE student_id = '$student_id' AND class_id = '$class_id' AND date = '$date'";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows > 0) {
            // Attendance record already exists, skip this insertion
            echo "Attendance for selected class on {$date} already exists.";
        } else {
            // No existing record, proceed with the insertion
            $sql = "INSERT INTO attendance (student_id, class_id, date, status) VALUES ('$student_id', '$class_id', '$date', '$status')";
            if ($conn->query($sql) === TRUE) {
                echo "Attendance marked successfully!<br>";
            } else {
                echo "Error marking attendance: " . $conn->error . "<br>";
            }
        }
    }
}

    ?>
</div>

<!-- View Attendance History Section -->
<div class="container">
    <h2>View Attendance History</h2>
    <form method="get" action="mark_attendance.php">
        <label for="filter_by">Filter By:</label>
        <select name="filter_by" id="filter_by" required>
            <option value="student">Student Name</option>
            <option value="date">Date</option>
            <option value="class">Class</option>
        </select>
        <div id="filter_value_container">
            <label for="filter_value">Value:</label>
            <input type="text" name="filter_value" id="filter_value" placeholder="Enter Student Name, Date (YYYY-MM-DD), or Class" required>
        </div>
        <button type="submit">View History</button>
    </form>

    <?php
    if (isset($_GET['filter_by']) && isset($_GET['filter_value'])) {
        $filter_by = $_GET['filter_by'];
        $filter_value = $_GET['filter_value'];

        // SQL query based on filter type
        if ($filter_by === 'student') {
            $result = $conn->query("SELECT attendance.*, students.name, students.course, classes.name AS class_name
                                FROM attendance 
                                JOIN students ON attendance.student_id = students.id
                                JOIN classes ON attendance.class_id = classes.id
                                WHERE students.name LIKE '%$filter_value%' 
                                ORDER BY attendance.date DESC");
            $filter_label = "Attendance History for Student: $filter_value";
        } else if ($filter_by === 'date') {
            $result = $conn->query("SELECT attendance.*, students.name, students.course, classes.name AS class_name
                                FROM attendance 
                                JOIN students ON attendance.student_id = students.id 
                                JOIN classes ON attendance.class_id = classes.id
                                WHERE attendance.date = '$filter_value' 
                                ORDER BY students.name ASC");
            $filter_label = "Attendance on Date: $filter_value";
        } else if ($filter_by === 'class') {
            $result = $conn->query("SELECT attendance.*, students.name, students.course, classes.name AS class_name
                                FROM attendance 
                                JOIN students ON attendance.student_id = students.id 
                                JOIN classes ON attendance.class_id = classes.id
                                WHERE classes.name LIKE '%$filter_value%' 
                                ORDER BY attendance.date DESC");
            $filter_label = "Attendance History for Class: $filter_value";
        }

        echo "<h3>$filter_label</h3>";
        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Course</th>
                        <th>Class</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>";

            $counter = 1;

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$counter}
                        <td>{$row['name']}</td>
                        <td>{$row['course']}</td>
                        <td>{$row['class_name']}</td>
                        <td>{$row['status']}</td>
                        <td>{$row['date']}</td>
                    </tr>";
                    $counter++;
            }
            echo "</table>";
        } else {
            echo "<p>No attendance records found.</p>";
        }
    }
    ?>
</div>

</body>
</html>
