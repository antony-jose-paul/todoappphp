<?php
// Start session BEFORE any HTML output
session_start();

// Include the tasks handler
include 'tasks.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#667eea">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>My Tasks</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 0;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Mobile-first animated background */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 1px, transparent 1px);
            background-size: 40px 40px;
            animation: moveBackground 25s linear infinite;
            pointer-events: none;
        }
        
        @keyframes moveBackground {
            0% { transform: translate(0, 0); }
            100% { transform: translate(40px, 40px); }
        }
        
        .container {
            background: rgba(255, 255, 255, 0.98);
            width: 100%;
            min-height: 100vh;
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
        }
        
        /* Header section */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        h1 {
            color: white;
            text-align: center;
            font-size: 26px;
            font-weight: 700;
            margin: 0;
        }
        
        /* Search section */
        .search-section {
            padding: 16px;
            background: white;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 60px;
            z-index: 99;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .search-box {
            position: relative;
            width: 100%;
        }
        
        .search-box input {
            width: 100%;
            padding: 14px 16px 14px 44px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }
        
        .search-box input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        
        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: #868e96;
            pointer-events: none;
        }
        
        .search-results-count {
            margin-top: 8px;
            font-size: 13px;
            color: #868e96;
            text-align: center;
        }
        
        /* Add task form */
        .add-task-section {
            padding: 16px;
            background: white;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 140px;
            z-index: 98;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .add-task-form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 100%;
        }
        
        .add-task-form input[type="text"] {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }
        
        .add-task-form input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        
        .add-task-form button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 17px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.2s ease;
            touch-action: manipulation;
        }
        
        .add-task-form button:active {
            transform: scale(0.98);
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
        }
        
        /* Task list section */
        .task-list-section {
            flex: 1;
            padding: 16px;
            padding-bottom: 80px;
            overflow-y: auto;
        }
        
        .task-list {
            list-style: none;
        }
        
        .task-item {
            background: white;
            padding: 16px;
            margin-bottom: 12px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 2px solid #e2e8f0;
            transition: all 0.2s ease;
            animation: taskSlideIn 0.3s ease-out;
            touch-action: manipulation;
        }
        
        @keyframes taskSlideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .task-item:active {
            transform: scale(0.99);
            background: #f8f9fa;
        }
        
        .task-item.completed {
            opacity: 0.6;
            background: #f1f3f5;
        }
        
        .task-item.completed .task-text {
            text-decoration: line-through;
            color: #868e96;
        }
        
        .task-item.hidden {
            display: none;
        }
        
        .task-checkbox {
            width: 28px;
            height: 28px;
            min-width: 28px;
            cursor: pointer;
            accent-color: #667eea;
        }
        
        .task-text {
            flex: 1;
            font-size: 16px;
            color: #2d3748;
            font-weight: 500;
            line-height: 1.5;
            word-break: break-word;
            min-width: 0;
        }
        
        .delete-btn {
            background: linear-gradient(135deg, #f56565 0%, #c53030 100%);
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(245, 101, 101, 0.3);
            transition: all 0.2s ease;
            white-space: nowrap;
            touch-action: manipulation;
        }
        
        .delete-btn:active {
            transform: scale(0.95);
            box-shadow: 0 1px 4px rgba(245, 101, 101, 0.3);
        }
        
        .empty-state {
            text-align: center;
            color: #868e96;
            padding: 60px 20px;
            font-size: 16px;
            font-weight: 500;
            animation: fadeIn 0.5s ease-out;
        }
        
        .empty-state::before {
            content: '📋';
            display: block;
            font-size: 64px;
            margin-bottom: 16px;
            animation: bounce 2s infinite;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }
        
        /* Desktop adjustments (secondary) */
        @media (min-width: 768px) {
            .container {
                max-width: 600px;
                margin: 20px auto;
                min-height: calc(100vh - 40px);
                border-radius: 20px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            }
            
            .header {
                border-radius: 20px 20px 0 0;
            }
            
            h1 {
                font-size: 32px;
            }
            
            .search-section {
                position: relative;
                top: 0;
                padding: 16px 24px;
            }
            
            .add-task-section {
                position: relative;
                top: 0;
                padding: 24px;
            }
            
            .add-task-form {
                flex-direction: row;
            }
            
            .add-task-form button {
                width: auto;
                padding: 14px 32px;
            }
            
            .task-list-section {
                padding: 24px;
            }
            
            .task-item:hover {
                border-color: #667eea;
                box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
            }
        }
        
        /* Safe area insets for notched phones */
        @supports (padding: max(0px)) {
            .header {
                padding-top: max(20px, env(safe-area-inset-top));
            }
            
            .task-list-section {
                padding-bottom: max(80px, env(safe-area-inset-bottom));
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📝 My Tasks</h1>
        </div>
        
        <div class="search-section">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input type="text" id="searchInput" placeholder="Search tasks..." autocomplete="off">
            </div>
            <div class="search-results-count" id="searchCount"></div>
        </div>
        
        <div class="add-task-section">
            <form action="tasks.php" method="POST" class="add-task-form">
                <input type="text" name="task" placeholder="What needs to be done?" required autocomplete="off">
                <input type="hidden" name="action" value="add">
                <button type="submit">Add Task</button>
            </form>
        </div>
        
        <div class="task-list-section">
            <ul class="task-list" id="taskList">
                <?php
                $tasks = getTasks();
                
                if (empty($tasks)) {
                    echo '<div class="empty-state" id="emptyState">No tasks yet. Add your first task above!</div>';
                } else {
                    foreach ($tasks as $index => $task) {
                        $completedClass = $task['completed'] ? 'completed' : '';
                        $checked = $task['completed'] ? 'checked' : '';
                        
                        echo '<li class="task-item ' . $completedClass . '" data-task-text="' . htmlspecialchars($task['text']) . '">';
                        echo '<form action="tasks.php" method="POST" style="display: inline;">';
                        echo '<input type="hidden" name="action" value="toggle">';
                        echo '<input type="hidden" name="index" value="' . $index . '">';
                        echo '<input type="checkbox" class="task-checkbox" onchange="this.form.submit()" ' . $checked . '>';
                        echo '</form>';
                        echo '<span class="task-text">' . htmlspecialchars($task['text']) . '</span>';
                        echo '<form action="tasks.php" method="POST" style="display: inline;">';
                        echo '<input type="hidden" name="action" value="delete">';
                        echo '<input type="hidden" name="index" value="' . $index . '">';
                        echo '<button type="submit" class="delete-btn">Delete</button>';
                        echo '</form>';
                        echo '</li>';
                    }
                }
                ?>
            </ul>
        </div>
    </div>
    
    <script>
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const taskList = document.getElementById('taskList');
        const searchCount = document.getElementById('searchCount');
        const emptyState = document.getElementById('emptyState');
        
        function performSearch() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const taskItems = taskList.querySelectorAll('.task-item');
            let visibleCount = 0;
            const totalCount = taskItems.length;
            
            taskItems.forEach(item => {
                const taskText = item.getAttribute('data-task-text').toLowerCase();
                
                if (searchTerm === '' || taskText.includes(searchTerm)) {
                    item.classList.remove('hidden');
                    visibleCount++;
                } else {
                    item.classList.add('hidden');
                }
            });
            
            // Update search count
            if (totalCount === 0) {
                searchCount.textContent = '';
                if (emptyState) {
                    emptyState.style.display = 'block';
                }
            } else if (searchTerm !== '') {
                searchCount.textContent = `Showing ${visibleCount} of ${totalCount} tasks`;
                if (emptyState) {
                    emptyState.style.display = 'none';
                }
                
                // Show "no results" message if nothing found
                if (visibleCount === 0) {
                    if (!document.getElementById('noResults')) {
                        const noResults = document.createElement('div');
                        noResults.id = 'noResults';
                        noResults.className = 'empty-state';
                        noResults.innerHTML = '🔍<br>No tasks found matching your search.';
                        noResults.style.setProperty('display', 'block', 'important');
                        taskList.appendChild(noResults);
                    }
                } else {
                    const noResults = document.getElementById('noResults');
                    if (noResults) {
                        noResults.remove();
                    }
                }
            } else {
                searchCount.textContent = `Total tasks: ${totalCount}`;
                const noResults = document.getElementById('noResults');
                if (noResults) {
                    noResults.remove();
                }
            }
        }
        
        // Add event listener for search input
        if (searchInput) {
            searchInput.addEventListener('input', performSearch);
            
            // Initialize count on page load
            const taskItems = taskList.querySelectorAll('.task-item');
            if (taskItems.length > 0) {
                searchCount.textContent = `Total tasks: ${taskItems.length}`;
            }
        }
    </script>
</body>
</html>