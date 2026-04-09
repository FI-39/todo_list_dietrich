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

function fetchTodos() {
    fetch(apiUrl)
        .then(response => response.json())
        .then(todos => {
            const todoList = document.getElementById('todo-list');
            todoList.innerHTML = '';
            todos.forEach(todo => {
                const li = document.createElement('li');
                li.textContent = todo;
                todoList.appendChild(li);
            });
        });
}

window.addEventListener("load", (event) => {
        console.log(event);
        fetchTodos();
});