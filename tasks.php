<?php
// tasks.php - Backend handler for to-do list

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize tasks array if not exists
if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add':
            $taskText = trim($_POST['task'] ?? '');
            if (!empty($taskText)) {
                $_SESSION['tasks'][] = [
                    'text' => $taskText,
                    'completed' => false
                ];
            }
            break;
            
        case 'toggle':
            $index = (int)($_POST['index'] ?? -1);
            if (isset($_SESSION['tasks'][$index])) {
                $_SESSION['tasks'][$index]['completed'] = !$_SESSION['tasks'][$index]['completed'];
            }
            break;
            
        case 'delete':
            $index = (int)($_POST['index'] ?? -1);
            if (isset($_SESSION['tasks'][$index])) {
                array_splice($_SESSION['tasks'], $index, 1);
            }
            break;
    }
    
    // Redirect back to index.php to prevent form resubmission
    header('Location: index.php');
    exit;
}

// Function to get all tasks
function getTasks() {
    return $_SESSION['tasks'] ?? [];
}
?>