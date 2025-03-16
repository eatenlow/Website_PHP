<?php
    global $result, $stmt, $conn;
    // Create database connection.
    $config = parse_ini_file('/var/www/private/db-config.ini');
    if (!$config){
        $errorMsg = "Failed to read database config file.";
    }
    else{
        $conn = new mysqli(
        $config['servername'],
        $config['username'],
        $config['password'],
        $config['dbname']
        );
        // Check connection
        if ($conn->connect_error){
            $errorMsg = "Connection failed: " . $conn->connect_error;
        }
        else{
            // Prepare the statement:
            $stmt = $conn->prepare("SELECT * FROM pets");
            
            if (!$stmt->execute()){
                $errorMsg = "Execute failed: (" . $stmt->errno . ") ";
                $stmt->error;
            }
            $result = $stmt->get_result();

            $stmt->close();
        }
    $conn->close();
    }
?>

<html>
    <head>
        <?php 
            include 'inc/head.inc.php';
            include 'inc/navbar.inc.php'; 
        ?>
    </head>
    <body>
        <main class="container-lg w-60 w-md-80 w-sm-90 w-100 mx-auto">
            <h1><strong>Edit Listings</strong></h1>
            <?php if ($result->num_rows > 0): ?>
            <table style="width:100%;">
                <tr>
                    <th>Pet ID</th>
                    <th>Pet Name</th>
                    <th>Pet type</th>
                    <th>Breed</th>
                    <th>Birthdate</th>
                    <th>Gender</th>
                    <th>Adoption Cost</th>
                </tr>

            
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row["pet_ID"]) ?></td>
                    <td><?= htmlspecialchars($row["pet_name"]) ?></td>
                    <td><?= htmlspecialchars($row["pet_type"]) ?></td>
                    <td><?= htmlspecialchars($row["breed"]) ?></td>
                    <td><?= htmlspecialchars($row["age"]) ?></td>
                    <td><?= htmlspecialchars($row["gender"]) ?></td>
                    <td><?= htmlspecialchars($row["adopt_cost"]) ?></td>
                    <?php
                    echo '<td> <a href=/editList?id='.$row["pet_ID"].'><i class="bi bi-pencil-square"></i></a></td>';
                    ?>
                </tr>
            <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No listings found.</td>
                </tr>
            </table>
            <?php endif; ?>
        </main>
    <?php
        //$stmt->close();
        // $conn->close();
        include "inc/footer.inc.php";
    ?>
    </body>
</html>
