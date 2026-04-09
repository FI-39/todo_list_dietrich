const apiUrl = 'todo_api.php';

document.getElementById('todo-form').addEventListener('submit', function (e) {
    e.preventDefault();
 
    const todoInput = document.getElementById('todo-input').value;
    fetch(apiUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ todo: todoInput }),
    })
    .then(response => response.json())
    .then((data) => {
        console.log(data);
        fetchTodos();
        document.getElementById('todo-input').value = '';
    });
});
 