# TODO App in PHP

## Quick Start
Make Sure you have Xampp Server in your machine.

Start the Apache and MySQL services in your Xampp Control Panel.

Then in your PHPMyAdmin, create a database with the name of `todo_query`.

Click on `todo_query` and click the SQL Tab.

Paste this code on the SQL Tab:
```sql
USE todo_query;

CREATE TABLE todo_query.todos (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    todo VARCHAR(8000) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

Then click the Go button below.

