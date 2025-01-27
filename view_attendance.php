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
    <a href="create_class.php">Create class</a>
    <a href="add_student.php">Add Student</a>
    <a href="view_students.php">View Students</a>
    <a href="mark_attendance.php">Mark Attendance</a>
    <a class="active" href="view_attendance.php">View Attendance Reports</a>
</nav>
<header>
    <h1>Attendance Reports</h1>
</header>

<div class="container">
    <h2>Filter Attendance Reports (No Filter Applied: All students)</h2>
    <form method="GET">
        <label for="filter_type">Filter By:</label>
        <select name="filter_type" id="filter_type" required>
            <option value="All">All Students</option>
            <option value="class" <?= (isset($_GET['filter_type']) && $_GET['filter_type'] === 'class') ? 'selected' : '' ?>>Class</option>
            <option value="student" <?= (isset($_GET['filter_type']) && $_GET['filter_type'] === 'student') ? 'selected' : '' ?>>Student</option>
            <option value="date" <?= (isset($_GET['filter_type']) && $_GET['filter_type'] === 'date') ? 'selected' : '' ?>>Date</option>
        </select>

        <label for="filter_value">Value:</label>
        <input type="text" name="filter_value" id="filter_value" value="<?= isset($_GET['filter_value']) ? $_GET['filter_value'] : '' ?>" placeholder="Enter 'All', Class Name, Student Name or Date (YYYY-MM-DD)" required>

        <button type="submit">Filter</button>
    </form>

    <h2>Students Attendance</h2>
    <table>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Course</th>
            <th>Class</th>
            <th>Classes Attended</th>
            <th>Total Classes</th>
            <th>Attendance (%)</th>
            <th>Action</th>
        </tr>
        <?php
        // Initialize base query
        $query = "SELECT students.id, students.name, students.course, classes.name AS class_name, 
                         COUNT(CASE WHEN attendance.status = 'Present' THEN 1 END) AS classes_attended, 
                         COUNT(attendance.status) AS total_classes 
                  FROM students 
                  LEFT JOIN attendance ON students.id = attendance.student_id 
                  LEFT JOIN classes ON attendance.class_id = classes.id";

        // Apply filter if selected
        if (isset($_GET['filter_type']) && isset($_GET['filter_value'])) {
            $filter_type = $_GET['filter_type'];
            $filter_value = $_GET['filter_value'];

            if ($filter_type === 'class') {
                $query .= " WHERE classes.name LIKE '%$filter_value%'";
            } elseif ($filter_type === 'student') {
                $query .= " WHERE students.name LIKE '%$filter_value%'";
            } elseif ($filter_type === 'date') {
                $query .= " WHERE attendance.date = '$filter_value'";
            }
        }

        $query .= " GROUP BY students.id, classes.id";
        $students = $conn->query($query);

        $counter = 1;

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
                <td>{$counter}</td>
                <td>{$row['name']}</td>
                <td>{$row['course']}</td>
                <td>{$row['class_name']}</td>
                <td>{$row['classes_attended']}</td>
                <td>{$row['total_classes']}</td>
                <td>" . number_format($attendance_percentage, 2) . "%</td>
                <td><a href='delete_attendance.php?id={$row['id']}' onclick='return confirmDelete();'>Delete</a></td>
            </tr>";
            $counter++;
        }
        ?>
    </table>
</div>
</body>
</html>
