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
// Check request method with switsch-case
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Placeholder for reading TODO items
        // read data from request body
        $data = file_get_contents('php://input');
        break;
    case 'POST':
        // Placeholder for creating a new TODO
        // transform json data to array
        $input = json_decode($data, true);
        break;
    case 'PUT':
        // Placeholder for updating a TODO
        // add new entry to the todo array
        $todos[] = $input['todo'];
        break;
    case 'PATCH':
        // Placeholder for a partial update of a TODO
        // write json data to file
        file_put_contents($todo_file, json_encode($todos));
        break;
    case 'DELETE':
        // Placeholder for deleting a TODO
        // return success response
        echo json_encode(['status' => 'success']);
        break;
}

header('Content-Type: application/json');
 
// Read current todos from json file.
$todo_file = 'todo.json';
if (file_exists($todo_file)) {
    $json_data = file_get_contents($todo_file);
    $todos = json_decode($json_data, true);
} else {
    $todos = [];
}
?>