<?php
header('Content-Type: application/json');

$studentsFile = 'students.json';

// Ensure students.json exists
if (!file_exists($studentsFile)) {
    file_put_contents($studentsFile, json_encode([]));
}

// Load students data
$studentsData = json_decode(file_get_contents($studentsFile), true) ?: [];

// Handle GET requests (Retrieve students or delete student)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $studentsData = array_values(array_filter($studentsData, fn($s) => $s['id'] !== $id));
        file_put_contents($studentsFile, json_encode($studentsData, JSON_PRETTY_PRINT));
        echo json_encode(['status' => 'success', 'message' => 'Student deleted successfully.']);
        exit();
    }
    echo json_encode($studentsData);
    exit();
}

// Handle POST requests (Add or Edit student)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    $age = $_POST['age'] ?? '';
    $class = $_POST['class'] ?? '';

    if (empty($name) || empty($age) || empty($class)) {  
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit();
    }

    if (!is_numeric($age) || $age < 0) {
        echo json_encode(['status' => 'error', 'message' => 'Age must be a valid positive number.']);
        exit();
    }

    if (!is_numeric($class) || $class < 1 || $class > 12) {  
        echo json_encode(['status' => 'error', 'message' => 'Class must be between 1 and 12.']);
        exit();
    }

    if ($id) {
        // Editing an existing student
        $found = false;
        foreach ($studentsData as &$student) {
            if ($student['id'] === $id) {
                $student['name'] = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
                $student['age'] = (int)$age;
                $student['class'] = (int)$class;
                $found = true;
                break;
            }
        }

        if (!$found) {
            echo json_encode(['status' => 'error', 'message' => 'Student not found.']);
            exit();
        }
        $message = 'Student updated successfully.';
    } else {
        // Adding a new student
        $id = uniqid(); // Generate unique ID
        $studentsData[] = [
            'id' => $id,
            'name' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
            'age' => (int)$age,
            'class' => (int)$class  
        ];
        $message = 'Student added successfully.';
    }

    file_put_contents($studentsFile, json_encode($studentsData, JSON_PRETTY_PRINT));
    echo json_encode(['status' => 'success', 'message' => $message]);
    exit();
}

echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
?>
