<?php
include 'database.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $_POST['subject'];
    $task = $_POST['task'];
    $due_date = $_POST['due_date'];

    try {
        $stmt = $conn->prepare("INSERT INTO study_tasks (subject, task, due_date) VALUES (?, ?, ?)");
        $stmt->execute([$subject, $task, $due_date]);

        // Redirect to avoid resubmission on refresh
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
        exit;
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Fetch all tasks
$tasks = $conn->query("SELECT * FROM study_tasks ORDER BY due_date")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Study Planner</title>
    <style>
        /* Global styles */
    body {
        background-color: #121212;   /* dark background */
        color: #e0e0e0;             /* light grey text */
        font-family: Arial, sans-serif;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 30px;
    }

    /* Planner form box */
    .planner-form {
        background-color: #1e1e1e;   /* dark grey box */
        border: 1px solid #00ff66;   /* neon green border */
        border-radius: 10px;
        padding: 40px;
        max-width: 800px;
        width: 100%;
        box-shadow: 0 0 15px rgba(0, 255, 100, 0.3);
    }

    /* Heading */
    h1 {
        text-align: center;
        color: #00ff66;  /* neon green */
        margin-bottom: 20px;
    }

    /* Form groups */
    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #cfcfcf;
    }

    /* Input & textarea */
    input[type="text"], 
    input[type="date"], 
    textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #333;
        border-radius: 6px;
        background-color: #2b2b2b;
        color: #f5f5f5;
        font-size: 14px;
    }

    input::placeholder,
    textarea::placeholder {
        color: #888;
    }

    input:hover, textarea:hover {
        border-color: #00ff66;  /* neon green on hover */
    }

    /* Button */
    button {
        background-color: #068a3dff;  /* green */
        color: #fff;
        padding: 14px;
        font-size: 22px;
        font-weight: bold;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        width: 100%;  /* full width */
        margin-top: 10px;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    button:hover {
        background-color: #0b391eff;
        transform: scale(1.02);  /* slight zoom on hover */
    }

    /* Task list */
    .task-list {
        margin-top: 40px;
        
    }

    .task-item {
        background-color: #1a1a1a;
        border: 1px solid #00ff66;
        border-radius: 8px;
        padding: 15px;
        width: 800px;
        max-width: 800px;
        margin: 0 auto 15px auto;
        margin-bottom: 15px;
        box-shadow: 0 0 8px rgba(0, 255, 100, 0.15);
    }

    .task-subject {
        font-weight: bold;
        font-size: 1.2em;
        color: #00ff66;
        margin-bottom: 5px;
    }

    .task-due {
        font-style: italic;
        color: #bbb;
        margin-bottom: 10px;
    }

    .task-description {
        color: #ddd;
    }

    </style>
</head>
<body>
    <h1>Student Study Planner</h1>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Task added successfully!</div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    
    <div class="planner-form">
        <form method="POST">
            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" placeholder="e.g. Mathematics" required>
            </div>
            
            <div class="form-group">
                <label for="task">Task</label>
                <textarea id="task" name="task" rows="3" placeholder="Describe the study task..." required></textarea>
            </div>
            
            <div class="form-group">
                <label for="due_date">Due Date</label>
                <input type="date" id="due_date" name="due_date" required>
            </div>
            
            <button type="submit">Add To Planner</button>
            <button type="Search">Search for Detail</button> 
        </form>
    </div>
    
    <div class="task-list">
        <h2>Your Study Tasks</h2>
        
        <?php if (empty($tasks)): ?>
            <p>No tasks added yet.</p>
        <?php else: ?>
            <?php foreach ($tasks as $task): ?>
                <div class="task-item">
                    <div class="task-subject"><?php echo htmlspecialchars($task['subject']); ?></div>
                    <div class="task-due">Due: <?php echo date('m/d/Y', strtotime($task['due_date'])); ?></div>
                    <div class="task-description"><?php echo nl2br(htmlspecialchars($task['task'])); ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>