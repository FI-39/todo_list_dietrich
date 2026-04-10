<?php
header('Content-Type: application/json');

//Read current todos from JSON file
$todo_file = 'todo.json';

//function for pretty print 'ä, ü, ö'
function pretty_print_json($file, $data) {
    $options = JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT;
    file_put_contents($file, json_encode($data, $options));
}

//LOG-function to save every CRUD-operation (Create, Read, Update, Delete)
function crud_log($action, $data) {
    $log = fopen('log.txt', 'a');
    $timestamp = date('Y-m-d H:i:s');
    fwrite($log, "$timestamp - $action: " . json_encode($data) . "\n");
    fclose($log); //close log!!
}

//load data
if (file_exists($todo_file)) {
    $json_data = file_get_contents($todo_file);
    $todos = json_decode($json_data, true);
    if (!$todos) {
        $todos = []; }
} else {
    $todos = [];
}

//check request method with switch-case
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        //print out current todos
        echo json_encode($todos, JSON_UNESCAPED_UNICODE);
        crud_log('GET', "List requested");
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        //validate posted data BEFORE creating a new item (!!!)
        //reject empty or whitespace-only input
        if (empty($data['todo']) || trim($data['todo']) === '') {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'ToDo darf nicht leer sein!'
            ]);
            crud_log('ERROR', "Empty POST data.");
            exit;
        }
        //create new todo item with an id
        $new_todo = ["id" => uniqid(), "title" => $data['todo'], "completed" => false];

        //add created item to my todo list
        $todos[] = $new_todo;
        //write and save todo item in JSON file
        pretty_print_json($todo_file, $todos);
        //log new item
        crud_log('POST', $new_todo);
        echo json_encode($todos, JSON_UNESCAPED_UNICODE);
        break;

        case 'DELETE':
            //get data from input stream
            $data = json_decode(file_get_contents('php://input'), true);
            //filter list and remove choosen item
            $todos = array_values(array_filter($todos, fn($todo) => $todo['id']!== $data['id']));

            //write todos back to JSON file
            pretty_print_json($todo_file, $todos);
            crud_log("DELETE", $data);
            //tell client success of operation
            echo json_encode($todos, JSON_UNESCAPED_UNICODE);
            break;
            
        case 'PATCH':
            //get data from input stream
            $data = json_decode(file_get_contents('php://input'), true);

            foreach($todos as $index => $todo) {
                if ($todo['id'] === $data['id']) {
                    $todos[$index]["completed"] = $data["completed"];
                break;
            }
        }

        //write todos back to JSON file
        file_put_contents($todo_file, json_encode($todos));

        //tell client success of operation
        echo json_encode(['status' => 'success']);

        crud_log("PATCH", $data);
}
?>