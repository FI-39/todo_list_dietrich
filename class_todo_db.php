<?php
/**
 * Database handling for the todos in the FI38 demo project.
 *
 * All database functionality is defined here.
 *
 * @author US-FI39 <post@fi39-coding.com>
 * @property object $connection PDO connection to the MariaDB
 * @property object $stmt Database statement handler object.
 * @throws
 * @since 1.0
 */

class TodoDB {
    private $connection;
    private $stmt;

/**
 * Constructor of the TodoDB class.
 *
 * @param string $host Database host address.
 * @param string $db Database name.
 * @param string $user Database user.
 * @param string $pass Database password.
 */

public function __construct($host, $db, $user, $pass) {
    try {
        $this->connection = new PDO(
            "mysql:host=$host;dbname=$db;charset=utf8mb4",
            $user,
            $pass);
            $this->connection->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log($e->getMessage());
    }
}

/**
 * Prepare and execute the given sql statement.
 *
 * @param string $sql The sql statement.
 * @param array $params An array of the needed parameters.
 * @return object $stmt The executed statement.
 */

private function prepareExecuteStatement($sql, $params = []) {
    try {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch(PDOException $e) {
        error_log($e->getMessage());
        }
    }

    /**
 * Prepare and execute the function
 * for case 'GET'
 */

public function getTodos() {
    $sql = "SELECT * FROM todo ORDER BY id DESC";
    return $this->prepareExecuteStatement($sql)->fetchAll();
    }

public function createTodo($title, $completed = 0) {
    $sql = "INSERT INTO todo (title, completed) VALUES (:title, :completed)";

    return $this->prepareExecuteStatement(
        $sql,
        ["title" => $title, 
        "completed" => $completed
        ])->rowCount();
    }

    /**
     * method chaining (Methoden-Kettung)
     * allows to do several steps in one go
     * $this->prepareExecuteStatement($sql, ["id" => $id]) -> call helping function 
     * which was written in 'pdo.php' in lines 55, 56
     * rowCount() -> embedded function of PDO (database driver)
     * counts real number of rows which where changed or deleted
     */

public function deleteTodo($id) {
    $sql = "DELETE FROM todo WHERE id = :id";
    return $this->prepareExecuteStatement($sql, [
        "id" => $id])->rowCount();
}

public function updateStep($id, $completed) {       //PATCH
    $sql = "UPDATE todo SET completed = :completed WHERE id = :id";
    return $this->prepareExecuteStatement($sql, [
        "id" => $id,
        "completed" => $completed
    ])->rowCount();
}

public function updateData($id, $title) {          //PUT
    $sql = "UPDATE todo SET title = :title WHERE id = :id";
    return $this->prepareExecuteStatement($sql, [
        "id" => $id,
        "title" => $title
    ])->rowCount();
    }
}
?>

