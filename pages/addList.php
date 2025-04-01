<html lang="en">
    <head>
        <?php 
            include 'inc/head.inc.php';
            include 'inc/navbar.inc.php'; 
        ?>
    </head>
    <body>
        <main class="container-lg w-60 w-md-80 w-sm-90 w-100 mx-auto">
        <h1><strong>Add Listing</strong></h1>
        <a href="/manageList" class='btn btn-secondary mb-3'>Back to Manage Listing</a>
        <?php
            if(!isset($_SESSION["login"]) || ($_SESSION["admin"] != '1')){
                header("Location: /a");
            }
        ?>
            <form action="../backend/new_listing.php" method="post" enctype="multipart/form-data">
                <?php 
                    echo '
                    <form-label for="image">New Pet Image:</form-label>
                    <input type="file" name="image" id="image" aria-label="pet image upload">
                    <br><br>
                    ';
                ?>
                <div class="mb-3">
                    <form-label for="name">Pet Name:</form-label>
                    <input type="text" id=name name="name" aria-label='Pet name' value="<?= htmlspecialchars($name) ?>" class="form-control" maxlength="45" required>
                </div>

                <div class="mb-3">
                    <form-label for="type">Pet Type:</form-label>
                    <input type="text" id=type name="type" aria-label='pet type' value="<?= htmlspecialchars($type) ?>" class="form-control" maxlength="45" required>
                </div>

                <div class="mb-3">
                    <form-label for="breed">Breed:</form-label>
                    <input type="text" id=breed name="breed" aria-label='breed' value="<?= htmlspecialchars($breed) ?>" class="form-control" maxlength="45" required>
                </div>

                <div class="mb-3">
                    <form-label for="age">Age:</form-label>
                    <input type="number" id=age name="age" aria-label='age' value="<?= htmlspecialchars($age) ?>" class="form-control" maxlength="45" required>
                </div>

                <div class="mb-3">
                    <form-label for="gender">Gender:</form-label>
                    <?php 
                        echo '
                        <select name="gender" id="gender" aria-label="gender" value='.$gender.'>
                          <option value="Male">Male</option>
                          <option "Female">Female</option>
                        </select>
                        ';
                    ?>
                </div>

                <div class="mb-3">
                    <form-label for="cost">Adoption Cost:</form-label>
                    <input type="number" id=cost name="cost" aria-label='cost' value="<?= htmlspecialchars($cost) ?>" class="form-control" maxlength="45" required>
                </div>

                <div class="mb-3">
                    <form-label for="desc">Description:</form-label>
                    <input type="text" id=desc name="desc" aria-label='description' value="<?= htmlspecialchars($desc) ?>" class="form-control" required>
                </div>

                <button class="btn btn-primary" type="submit" aria-label='add new listing' name='submit'>Add new Listing</button>
            </form>
        </main>
    <?php
    include "inc/footer.inc.php";
    ?>
    </body>
</html>
