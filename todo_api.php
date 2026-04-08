<?php
header('Content-Type: application/json');

// read current todos from json file
$todo_file = 'todo.json';
if (file_exists($todo_file)) {
    $json_data = file_get_contents($todo_file);
    $todos = json_decode($json_data, true);
} else {
    $todos = [];
}

// Add new todo item
// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // read data from request body
    $data = file_get_contents('php://input');
    // transform json data to array
    $input = json_decode($data, true);
    // add new entry to the todo array
    $todos[] = $input['todo'];
    // write json data to file
    file_put_contents($todo_file, json_encode($todos));
    // return success response
    echo json_encode(['status' => 'success']);
    exit;
}
 
// Print out the current todos.
echo json_encode($todos);