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

const getDeleteButton = (item) => {
    const deleteButton = document.createElement('button');
    deleteButton.textContent = 'Löschen';
    
    // Handle delete button click
    deleteButton.addEventListener('click', function() {
        console.log("Löschen-Button geklickt für Item: ", item);

    fetch(apiUrl, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: item.id })
        })
        .then(response => response.json())
        .then(() => {
            fetchTodos(); // Reload todo list
        });
    });

    return deleteButton;
}

const getCompleteButton = (item) => {
    const completeButton = document.createElement('button');
    completeButton.textContent = item.completed ?'Unerledigt' : 'Erledigt';
    //Handle complete button click
    completeButton.addEventListener('click', function() {
    fetch(apiUrl, {
    method: 'PATCH',
    headers: {
    'Content-Type': 'application/json'
    },
    body: JSON.stringify({ id: item.id, completed: !item.completed })
})
    .then(response => response.json())
    .then((data) => {

    console.log(data);
    fetchTodos(); // Reload todo list
    });
});
    return completeButton;
}

const getUpdateButton = (item) => {
    const updateButton = document.createElemenet('button');
    updateButton.textContent = 'Update';

    //handle update button klick
    updateButton.addEventListener('click', () => {
        document.getElementById('todo-id').value = item.id;
        document.getElementById('todo-update-input').value = item.title;
        document.getElementById('todo-form').style.display = 'none';
        document.getElementById('todo-update-form').style.display = 'blcok';
    });

    return updateButton;
}


function fetchTodos() {
    fetch(apiUrl)
        .then(response => response.json())
        .then(todos => {
            const todoList = document.getElementById('todo-list');
            todoList.innerHTML = '';

            todos.forEach(todo => {
                const li = document.createElement('li');
                li.id = todo.id;
                li.textContent = todo.title;

                if (todo.completed) {
                    li.textContent += " -> erledigt :-)";
                    li.style.textDecoration = 'line-through';
                }

                if (!todo.completed) {
                    li.appendChild(getCompleteButton(todo));
                }
                li.appendChild(getDeleteButton(todo));
                li.appendChild(getUpdateButton(item));

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