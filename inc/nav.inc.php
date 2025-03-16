<?php
session_start();
?>

<nav class="navbar navbar-expand-sm bg-secondary" data-bs-theme="dark">
    <img class="navbar-brand img-fluid" src="images/logo.png" height="1%">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="/">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#dogs">Dogs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#cats">Cats</a>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto">
            <?php 
            //if(!isset($_COOKIE["MYCOOKIE"])){
            if(!isset($_SESSION["login"])){
                echo "<li class=nav-item>
                    <a class='nav-link' href=/login>Login</a>
                </li>
                <li class='nav-item'>
                    <a class=nav-link href=/register>Sign Up</a>
                </li>";
            }
            else{
                if(isset($_SESSION["admin"])){
                    echo"<li class=nav-item>
                    <a class=nav-link href=admin.php>Admin</a>
                </li>";
                }
                echo"<li class=nav-item>
                    <a class=nav-link href=/profile>Profile</a>
                </li>
                <li class=nav-item>
                <a class=nav-link href=logout.php>logout</a>
            </li>";
            }
            ?>
        </ul>
    </div>
</nav>