<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>View Students</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/script.js"></script>
</head>
<body>
<div id="menu-icon" class="menu-container" onclick="toggleSidebar(this)">
    <div class="bar bar1"></div>
    <div class="bar bar2"></div>
    <div class="bar bar3"></div>
</div>
<nav id="sidebar" class="sidebar">
    <a href="index.php">Home</a>
    <a href="create_class.php">Create class</a>
    <a href="add_student.php">Add Student</a>
    <a class="active" href="view_students.php">View Students</a>
    <a href="mark_attendance.php">Mark Attendance</a>
    <a href="view_attendance.php">View Attendance Reports</a>
</nav>
<header>
    <h1>View Students</h1>
</header>
<div class="container">
    <h2>Filter Students by Class</h2>
    <form method="GET" action="">
        <label for="class_id">Select Class:</label>
        <select name="class_id" id="class_id">
            <option value="">All Classes</option>
            <?php
            // Fetch all classes from the database
            $class_result = $conn->query("SELECT id, name FROM classes");
            while ($class = $class_result->fetch_assoc()) {
                $selected = (isset($_GET['class_id']) && $_GET['class_id'] == $class['id']) ? 'selected' : '';
                echo "<option value='{$class['id']}' $selected>{$class['name']}</option>";
            }
            ?>
        </select>
        <button type="submit">Filter</button>
    </form>

    <h1>All Students</h1>
    <table border="1">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Course</th>
            <th>Class</th>
            <th>Action</th>
        </tr>

        <?php
        // Determine the filter criteria
        $filter = '';
        if (isset($_GET['class_id']) && $_GET['class_id'] != '') {
            $class_id = $conn->real_escape_string($_GET['class_id']);
            $filter = "WHERE student_classes.class_id = '$class_id'";
        }

        // Query to fetch students based on the filter
        $sql = "SELECT students.id, students.name, students.course, classes.name AS class_name 
                FROM students
                LEFT JOIN student_classes ON students.id = student_classes.student_id
                LEFT JOIN classes ON student_classes.class_id = classes.id
                $filter";
        $result = $conn->query($sql);

        $counter = 1;

        // Display the students in the table
        while ($row = $result->fetch_assoc()) {
            $class_name = $row['class_name'] ?? 'Not Assigned';
            echo "<tr>
                    <td>{$counter}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['course']}</td>
                    <td>$class_name</td>
                    <td>
                        <a href='edit_student.php?id={$row['id']}'>Edit</a>
                        <a href='delete_student.php?id={$row['id']}' onclick='return confirmDelete();'>Delete</a>
                    </td>
                </tr>";
                $counter++;
        }
        ?>
    </table>
</div>
</body>
</html>
