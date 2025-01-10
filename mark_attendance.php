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
</body>
</html>
