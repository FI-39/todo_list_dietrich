<?php
header('Content-Type: application/json');
 
// Read current todos from json file.
$todo_file = 'todo.json';
if (file_exists($todo_file)) {
    $json_data = file_get_contents($todo_file);
    $todos = json_decode($json_data, true);
    if (!$todos) {
        $todos = []; }
} else {
    $todos = [];
}

// LOG function -> protokolls every CRUD-Operation (Create, Read, Update, Delete)
function crud_log($action, $data) {
    $log = fopen('log.txt', 'a');
    $timestamp = date('Y-m-d H:i:s');
    fwrite($log, "$timestamp - $action: " . json_encode($data) . "\n");
}

// check request method with switch-case
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Print out the current todos.
        // echo json_encode($todos);
        crud_log('GET', $todos);
        break;
    case 'POST':
        /* first version:
        // Add new todo item
        // read data from request body
        $data = file_get_contents('php://input');
        // transform json data to array
        $input = json_decode($data, true);
        // add new entry to the todo array
        $todos[] = $input['todo'];
        // write json data to file
        save_json($todo_file, $todos);
        // return success response
        // echo json_encode(['status' => 'success']);
        */
        // updated version:
        $data = json_decode(file_get_contents('php://input'), true);

        // validate posted data BEFORE creating a new item (!!)
        // reject empty or whitespace-only input
        if (empty($data['todo']) || trim($data['todo']) === '') {
            http_response_code(400);
            echo json_encode(['status' => 'error',
                                          'message' => 'ToDo darf nicht leer sein.']);
            crud_log('ERROR', "Empty POST data.");
            exit;
        }
        
        // create new todo item
        $new_todo = ["id" => uniqid(), "title" => $data['todo']];

        // add new item to my todo item list
        $todos[] = $new_todo;

        // write todo item to JSON file
        file_put_contents($todo_file, json_encode($todos));

        // print out the new item
        // echo json_encode(["status" => "success"]);

        crud_log('POST', $todos);
        break;
    // Achtung: restliche cases 'PUT', 'PATCH', 'DELETE' zwischengespeichert in /code_snippets/api.php unter 2.
}

// Ausgabe
echo json_encode($todos, JSON_UNESCAPED_UNICODE);

// Hilfsfunktion für korrekte Darstellung von Umlauten
function save_json($file, $data) {
    $options = JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT;
    file_put_contents($file, json_encode($data, $options));
}
?>