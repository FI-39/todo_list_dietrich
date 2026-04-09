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
    .then(response => {
        // check if server responses an error (like 400)
        if (!response.ok) {
            // get JSON error message from server
            return response.json()
            .then(err => {
                // throw error
                throw new Error(err.message || 'Ein Fehler ist aufgetreten');
            });
        }
            // console.log("Successfully saved new todo!");
            return response.json();
    })
    .then((data) => {
        console.log("Successfully saved new todo!", data);
        fetchTodos();
        document.getElementById('todo-input').value = '';
    })
    .catch(error => {
        // error messge as popup
        displayMessage(error.message);
        console.warn("Validierungsfehler: ", error.message);
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

function displayMessage(text, isError = true) {
    const messageDiv = document.getElementById('message');
    if (!messageDiv) return;

    messageDiv.textContent = text;
    messageDiv.style.color = isError ? 'red' : 'green';

    // Nach 3000 Millisekunden (3 Sekunden) den Text wieder löschen
    setTimeout(() => {
        messageDiv.textContent = '';
        }, 3000);
    }

window.addEventListener("load", (event) => {
        console.log(event);
        fetchTodos();
});