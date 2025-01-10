<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/script.js"></script>
    <title>Attendance Reports</title>
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
            <!-- <th>ID</th> -->
            <th>Name</th>
            <th>Course</th>
            <th>Action</th>
            
        </tr>
        <?php
        $result = $conn->query("SELECT *,
                                (SELECT attendance.student_id 
                                    FROM attendance 
                                    WHERE attendance.student_id = students.id LIMIT 1) AS std_id
                                FROM students");
                                
        while ($row = $result->fetch_assoc()) {
        //    <td>{$row['id']}</td>  was removed from below
            echo "
                <tr>
                    <td>{$row['name']}</td>
                    <td>{$row['course']}</td>
                    <td><a href='delete_attendance.php?id={$row['id']}' onclick='return confirmDelete();'>Delete</a></td>
                </tr>";
        }
        ?>
    </table>
</div>

<div class="container">
    <h2>Students with Attendance Below 75%</h2>
    <table>
        <tr>
            <!-- <th>ID</th> -->
            <th>Name</th>
            <th>Course</th>
            <th>Classes Attended</th>
            <th>Total Classes</th>
            <th>Attendance (%)</th>
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

            // Display students with attendance below 75%
            // <td>{$row['id']}</td> was removed from below

            if ($attendance_percentage < 75) {
                echo "<tr>
                    
                    <td>{$row['name']}</td>
                    <td>{$row['course']}</td>
                    <td>{$row['classes_attended']}</td>
                    <td>{$row['total_classes']}</td>
                    <td>" . number_format($attendance_percentage, 2) . "%</td>
                </tr>";
            }
        }
        ?>
    </table>
</div>
</body>
</html>
