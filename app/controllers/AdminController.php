<?php

class AdminController
{
    function index() {
        include "app/views/Admin/index.php";
    }
    function manageMovie() {
        include "app/views/Admin/manage_movie.php";
    }
   
}
