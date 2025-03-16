
<html>
    <head>
        <title>Home | PetAdopt</title>
        <?php include 'inc/navbar.inc.php'; ?>
    </head>
    <body>
        <?php
        include "inc/nav.inc.php";
        ?>
        <main class="container">
        <?php
            session_destroy();
            echo "<h4>Bye!</h4>";
            header("Location: /");
        ?>
    </body>
</html>