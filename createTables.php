<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>database</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            box-sizing: border-box;
            overflow-x: hidden;
        }

        .table {
            /* width: 100%; */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 20px;
            padding: 20px;
            overflow-x: scroll;
        }

        .each {
            /* width: 100%; */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        table {
            width: 100%;
            max-width: 100vw;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 5px;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
            cursor: pointer;
        }

        h3 {
            padding: 10px;

        }

        i {
            padding: 0.5rem;
        }

        h3:hover {
            cursor: pointer;
        }
    </style>
</head>

<body>

    <div class="table">
        <h3>All tables</h3>

        <?php
        require_once 'CreateDb.php';

        // create instance of Createdb class
        // users table
        $sql1 = "CREATE TABLE IF NOT EXISTS users (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL,
            email VARCHAR(30) NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=INNODB;";
        // events table
        $sql2 = "CREATE TABLE IF NOT EXISTS events (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            user_id INT(11) NOT NULL,
            title VARCHAR(50) NOT NULL,
            date DATE NOT NULL,
            time TIME NOT NULL,
            description VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        ) ENGINE=INNODB;";
        /* $sql = "DROP TABLE IF EXISTS student;
        "; */
        $statements = [];

        $statements[] = $sql1;
        $statements[] =  $sql2;
        /*$statements[] =  $sql3;
        $statements[] =  $sql4;*/
        // $statements[] = $sql5;
        // $statements[] = $sql6;

        // print_r($statements);
        foreach ($statements as $s) {
            // echo $s;
            $sql = $s;
            $database = new CreateDb("group_calendar", "test", $sql);
        }
        // ---------------------------------------------------------------------------------------------
        // CRUD FUNCTIONS ---------------------------------------------------------------------------------------------

        // display specified database tables and their columns
        class Database
        {
            private $host = 'localhost';
            private $username = 'root';
            private $password = '';
            private $dbname = 'group_calendar';

            public function connect()
            {
                $conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                return $conn;
            }
        }
        $db = new Database();
        $sql_db = "SELECT * FROM information_schema.tables WHERE table_schema = 'group_calendar'";
        $result = $db->connect()->query($sql_db);
        echo '<h2>Available tables:</h2>';
        if ($result->num_rows > 0) {
            // fetch and display the tables with 5 row data each
            while ($row = $result->fetch_assoc()) {
                echo '<div class="each">
                        <h3>' . $row['TABLE_NAME'] . '</h3>';
                $sql_table = "SELECT * FROM information_schema.columns WHERE table_schema = 'group_calendar' AND table_name = '" . $row['TABLE_NAME'] . "'";
                $result_table = $db->connect()->query($sql_table);
                if ($result_table->num_rows > 0) {
                    // tmp table name
                    $tmp = [];
                    // echo table headers
                    echo '<table>
                        <tr>';
                    while ($row_table = $result_table->fetch_assoc()) {
                        // append column names to tmp array
                        $tmp[] = $row_table['COLUMN_NAME'];
                        echo '<th>' . $row_table['COLUMN_NAME'] . '</th>';
                    }
                    echo '</tr>';
                    // nwx
                    // get 5 table data
                    $t_name = $row['TABLE_NAME'];
                    $sql_data = "SELECT * FROM  $t_name LIMIT 10";
                    $result_data = $db->connect()->query($sql_data);
                    if ($result_data->num_rows > 0) {
                        // echo table data rows
                        while ($row_data = $result_data->fetch_assoc()) {
                            echo '<tr>';
                            // loop dynamically through column names
                            foreach ($tmp as $t) {
                                echo '<td>' . $row_data[$t] . '</td>';
                            }
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td>no data in table yet!</td></tr>';
                    }
                    // nwx
                    echo '</table>';
                    echo '</div>';
                }
            }
        } else {
            echo "0 results";
        }

        // truncate table
        function truncateTable($sql)
        {
            // sql for table to be truncated
            $sql = $sql;
            $dbt = new Database();

            if ($dbt->connect()->query($sql) === TRUE) {
                echo "Table truncated successfully";
            } else {
                echo "Error truncating table: " . $dbt->connect()->error;
            }
        }

        // call truncateTable function
        // truncateTable();

        // drop table
        function dropTable($sql)
        {
            $sql = $sql;
            $dbt = new Database();

            if ($dbt->connect()->query($sql) === TRUE) {
                echo "Table dropped successfully";
            } else {
                echo "Error dropping table: " . $dbt->connect()->error;
            }
        }

        // insert data into table
        function insertData($sql)
        {
            $sql = $sql;
            $dbt = new Database();

            if ($dbt->connect()->query($sql) === TRUE) {
                echo "Data inserted successfully";
            } else {
                echo "Error inserting data: " . $dbt->connect()->error;
            }
        }
        ?>
    </div>

</body>

</html>