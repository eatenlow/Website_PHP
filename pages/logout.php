
<html>
    <head>
        <?php 
            include 'inc/head.inc.php';
            include 'inc/navbar.inc.php'; 
        ?>
    </head>
    <body>
        <main class="container">
        <?php
            session_destroy();
            echo "<h4>Bye!</h4>";
            header("Location: /");
        ?>
    </body>
</html>