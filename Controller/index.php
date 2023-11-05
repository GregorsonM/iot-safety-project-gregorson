<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../Model/userModel.php'; // Include the model

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (createUser($username, $password)) {
        include '../View/signupSuccessView.php'; // Include the success view
    } else {
        include '../View/signupErrorView.php'; // Include the error view
    }
} else {
    include '../View/signupView.php'; // Include the initial sign-up form
}
