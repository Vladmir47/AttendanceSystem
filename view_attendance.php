<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/script.js"></script>
    <title>Attendance Reports</title>
    <style>
        .highlight {
            background-color: yellow; /* Highlight color for students with < 75% attendance */
        }
    </style>
</head>
<body>

<nav>
    <a href="index.php">Home</a>
    <a href="add_student.php">Add Student</a>
    <a href="view_students.php">View Students</a>
    <a href="mark_attendance.php">Mark Attendance</a>
    <a href="view_attendance.php">View Attendance Reports</a>
</nav>
<header>
    <h1>Attendance Reports</h1>
</header>

<div class="container">
    <h2>Students Attendance</h2>
    <table>
        <tr>
            <th>Name</th>
            <th>Course</th>
            <th>Classes Attended</th>
            <th>Total Classes</th>
            <th>Attendance (%)</th>
            <th>Action</th>
        </tr>
        <?php
        // Fetch students and their attendance records
        $students = $conn->query("SELECT students.id, students.name, students.course, 
                                  COUNT(CASE WHEN attendance.status = 'Present' THEN 1 END) AS classes_attended, 
                                  COUNT(attendance.status) AS total_classes 
                                  FROM students 
                                  LEFT JOIN attendance ON students.id = attendance.student_id 
                                  GROUP BY students.id");

        // Loop through students and calculate attendance percentage
        while ($row = $students->fetch_assoc()) {
            $attendance_percentage = 0;
            if ($row['total_classes'] > 0) {
                $attendance_percentage = ($row['classes_attended'] / $row['total_classes']) * 100;
            }

            // Determine row class based on attendance percentage
            $row_class = $attendance_percentage < 75 ? 'highlight' : '';

            // Display student information in the table
            echo "<tr class='$row_class'>
                <td>{$row['name']}</td>
                <td>{$row['course']}</td>
                <td>{$row['classes_attended']}</td>
                <td>{$row['total_classes']}</td>
                <td>" . number_format($attendance_percentage, 2) . "%</td>
                <td><a href='delete_attendance.php?id={$row['id']}' onclick='return confirmDelete();'>Delete</a></td>
            </tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
