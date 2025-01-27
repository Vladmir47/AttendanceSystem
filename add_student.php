<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/script.js"></script>
    <title>Add Student</title>
</head>
<body>

<nav>
    <a href="index.php">Home</a>
    <a href="create_class.php">Create class</a>
    <a class="active" href="add_student.php">Add Student</a>
    <a href="view_students.php">View Students</a>
    <a href="mark_attendance.php">Mark Attendance</a>
    <a href="view_attendance.php">View Attendance Reports</a>
</nav>
<header>
    <h1>Add Student</h1>
</header>

<div class="container">
    <form method="POST" onsubmit="return validateForm(this);">
        <label for="name">Student Name</label>
        <input type="text" name="name" id="name" required>
        
        <label for="course">Course</label>
        <input type="text" name="course" id="course" required>
        
        <label for="class">Class</label>
        <select name="class_id" id="class" required>
            <option value="">Select a class</option>
            <?php
            // Fetch classes from the database
            $sql = "SELECT id, name FROM classes";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
            }
            ?>
        </select>

        <button type="submit" name="submit">Add Student</button>
    </form>

    <?php
    if (isset($_POST['submit'])) {
        $name = trim($_POST['name']);
        $course = trim($_POST['course']);
        $class_id = $_POST['class_id'];

        // Check if the student already exists in the database
        $sql = "SELECT id FROM students WHERE name = '$name' AND course = '$course'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Student already exists
            $row = $result->fetch_assoc();
            $student_id = $row['id'];

            // Check if the student is already linked to the class
            $check_link_sql = "SELECT * FROM student_classes WHERE student_id = '$student_id' AND class_id = '$class_id'";
            $link_result = $conn->query($check_link_sql);

            if ($link_result->num_rows > 0) {
                echo "<p>Student already exists and is linked to the selected class.</p>";
            } else {
                // Link the student to the selected class
                $sql = "INSERT INTO student_classes (student_id, class_id) VALUES ('$student_id', '$class_id')";
                if ($conn->query($sql) === TRUE) {
                    echo "<p>Student linked to the class successfully!</p>";
                } else {
                    echo "<p>Error linking student to class: " . $conn->error . "</p>";
                }
            }
        } else {
            // Student does not exist, insert into the students table
            $sql = "INSERT INTO students (name, course) VALUES ('$name', '$course')";
            if ($conn->query($sql) === TRUE) {
                $student_id = $conn->insert_id;

                // Link the student to the selected class
                $sql = "INSERT INTO student_classes (student_id, class_id) VALUES ('$student_id', '$class_id')";
                if ($conn->query($sql) === TRUE) {
                    echo "<p>Student added and linked to class successfully!</p>";
                } else {
                    echo "<p>Error linking student to class: " . $conn->error . "</p>";
                }
            } else {
                echo "<p>Error adding student: " . $conn->error . "</p>";
            }
        }
    }
    ?>
</div>

</body>
</html>
