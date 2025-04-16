<!DOCTYPE html>
<html>
    <head>
        <title>My TODO App</title>
		<link rel="stylesheet" href="pico-main/css/pico.min.css" />
		<link rel="stylesheet" href="pico-main/css/pico.colors.min.css" />
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
        <style>
            #the_todo {
                margin-top: 1px;
                margin-left: 20px;
            }
            #clear {
                height: 60px;
                width: 60px;
                text-align: center;
                background-color: var(--pico-color-red-500);
                border-color: var(--pico-color-red-500);
            }
            
            .lists {
                align-content: center;
                align-items: center;
                display: inline-flex;
                
            }
            .listbox {
                display: flex;
                flex-direction: column-reverse;
            }
            
        </style>
    </head>
    <body>
        <header>
            <h1 align="center"> Todo App </h1>
		</header>
        <?php

            function displayConsole($msg) {
                echo "<script>console.log('" . $msg . "')</script>";
            }


            $host = "localhost";
            $user = "root";
            $pass = "";
            $db = "todo_query";

            $conn = new mysqli($host, $user, $pass, $db);

            if ($conn->connect_error) {
                die("ERROR: " . $conn->connect_error);
            } else {
                displayConsole("DATABASE CONNECTED SUCCESSFULLY.");
            }

            function storeTodos($conn, $todo) {
                $query = "INSERT INTO todos (todo) VALUES ('$todo')";
                $result = $conn->query($query);

                if ($result) {
                    displayConsole("Record added successfully.");
                } else {
                    displayConsole("Error: " . $conn->error);
                }
            }

            function removeTodos($conn, $idName) {
                if (isset($_POST[$idName])) {
                    $clearId = explode("-", $idName);
                    $query = "DELETE FROM todos WHERE id=" . $clearId[1];
                    $result = $conn->query($query);

                    if ($result) {
                        displayConsole("Deleted id " . $clearId[1]);
                        echo "<script>location.assign(window.location.href)</script>";
                    } else {
                        displayConsole("ERROR: " . $conn->error);
                    }
                }
            }

            function appendTodos($conn, $id, $todo) {
                $currentId = $id;
                $idName = "clear-$currentId";
                echo "<div class='lists'><form method='post'><button type='submit' name='$idName' id='clear' value='$id'>X</button></form><h3 id='the_todo'>$todo</h3></div>";
                removeTodos($conn, $idName);
            }



            if (isset($_POST['add'])) {
                storeTodos($conn, htmlspecialchars($_POST['todo_word']));
            }

        ?>
		<main class="container">
            <form role="group" method="post">
                <input type="text" placeholder="Add Todo Here." name="todo_word" autocomplete="off"/>
                <button type="submit" name="add">Add</button>
            </form>

            <br />
            <div class="container listbox">
                    <?php
                        $query = "SELECT * FROM todos";
                        $result = $conn->query($query);

                        while ($row = $result->fetch_assoc()) {
                            appendTodos($conn, $row['id'], $row['todo']);
                        }
                    ?>
            </div>

        </main>
        </center>
    </body>
</html>
