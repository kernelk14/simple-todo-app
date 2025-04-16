<!DOCTYPE html>
<html>
    <head>
        <title>My TODO App</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="pico-main/css/pico.min.css" />
		<link rel="stylesheet" href="pico-main/css/pico.colors.min.css" />
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
        <style>
            #the_todo {
                color: var(--pico-color-grey-200);
                margin-top: 1px;
                margin-left: 20px;
                margin-bottom: 2px;
            }
            #trash {
                height: 32px;
                width: 32px;
                margin-top: -4px;
/*                margin-bottom: 8px;*/
            }
            #clear {
                height: 60px;
                width: 60px;
                margin-bottom: 4px;
                margin-left: -4px;
                margin-right: 8px;
                text-align: center;
                background-color: var(--pico-color-red-500);
                border-color: var(--pico-color-red-500);
            }
            
            .lists {
                background-color: var(--pico-color-zinc-850);
                padding: 20px;
                border: 1px solid var(--pico-color-zinc-700);
                border-radius: 10px;
                margin-bottom: 10px;
                align-content: center;
                align-items: center;
                display: inline-flex;
                
            }
            .listbox {
                margin-top: 20px;
                display: flex;
                flex-direction: column-reverse;
            }
            
        </style>
    </head>
    <body>
        <header>
            <h1 align="center"> TODO </h1>
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
                echo "<div class='lists'><br /><form method='post' id='the_todo'><button type='submit' name='$idName' id='clear' value='$id'><img id='trash' src='images/trash-other.svg' /></button></form><h4 id='the_todo'>$todo</h4></div>";
                removeTodos($conn, $idName);
            }



            if (isset($_POST['add'])) {
                $words = $_POST['todo_word'];
                if (strlen($words) != 0) {
                    storeTodos($conn, htmlspecialchars($words));
                }
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
    </body>
</html>
