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
        <form method="POST" action="">
            <label>Date:</label>
            <input type="date" name="date" required><br><br>

            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Status</th>
                </tr>

                <?php
                $students = $conn->query("SELECT * FROM students");
                while ($student = $students->fetch_assoc()) {
                    echo "<tr>
                        <td>{$student['id']}</td>
                        <td>{$student['name']}</td>
                        <td>
                            <select name='status[{$student['id']}]'>
                                <option value='Present'>Present</option>
                                <option value='Absent'>Absent</option>
                                <option value='Late'>Late</option>
                            </select>
                        </td>
                    </tr>";
                }
                ?>
            </table>
            <button type="submit" name="submit">Submit Attendance</button>
        </form>

        <?php
        if (isset($_POST['submit'])) {
            $date = $_POST['date'];
            foreach ($_POST['status'] as $student_id => $status) {
                $sql = "INSERT INTO attendance (student_id, date, status) VALUES ('$student_id', '$date', '$status')";
                $conn->query($sql);
            }
            echo "Attendance marked successfully!";
        }
        ?>
    </div>


    <div class="container">
    <h2>View Attendance History</h2>
    <form method="get" action="mark_attendance.php">
        <label for="filter_by">Filter By:</label>
        <select name="filter_by" id="filter_by" required>
            <option value="student">Student Name</option>
            <option value="date">Date</option>
        </select>
        <div id="filter_value_container">
            <label for="filter_value">Value:</label>
            <input type="text" name="filter_value" id="filter_value" placeholder="Enter Student Name or Date (YYYY-MM-DD)" required>
        </div>
        <button type="submit">View History</button>
    </form>

    <?php
    if (isset($_GET['filter_by']) && isset($_GET['filter_value'])) {
        $filter_by = $_GET['filter_by'];
        $filter_value = $_GET['filter_value'];

        // SQL query based on filter type
        if ($filter_by === 'student') {
            $result = $conn->query("SELECT attendance.*, students.name, students.course 
                                FROM attendance 
                                JOIN students ON attendance.student_id = students.id 
                                WHERE students.name LIKE '%$filter_value%' 
                                ORDER BY attendance.date DESC");
            $filter_label = "Attendance History for Student: $filter_value";
        } else if ($filter_by === 'date') {
            $result = $conn->query("SELECT attendance.*, students.name, students.course 
                                FROM attendance 
                                JOIN students ON attendance.student_id = students.id 
                                WHERE attendance.date = '$filter_value' 
                                ORDER BY students.name ASC");
            $filter_label = "Attendance on Date: $filter_value";
        }

        echo "<h3>$filter_label</h3>";
        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>Student Name</th>
                        <th>Course</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['course']}</td>
                        <td>{$row['status']}</td>
                        <td>{$row['date']}</td>
                    </tr>";
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
