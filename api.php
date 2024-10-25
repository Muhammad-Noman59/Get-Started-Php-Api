<?php



header('Content-Type: application/json');


$response = [];


$api_key = $_SERVER["HTTP_API_KEY"] ?? '';

$vaild_api_key = "qwertyuklpsdfnyudsgfhkdsnvcjkrgewgwgretw";

if ($api_key !== $vaild_api_key) {

    header('HTTP/1.1 401 Unauthorized request');
    $response['message'] = "Invaild api key";
    echo json_encode($response);
    die();
}

require_once('db.php');


$action = $_GET['action'];

if ($action == "create_user") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];


    $image_name = $_FILES['image']['name'];

    $tmp = explode(".", $image_name);

    $extension = end($tmp);


    $newFileName = round(microtime(true)) . "." . $extension;

    $uploadPath = "uploads/" . $newFileName;

    $fullImageURL = 'https://xyz.com/phptest/' . $uploadPath;

    move_uploaded_file($_FILES['image']["tmp_name"], $uploadPath);

    $query = "Insert into users (name, email, password, image) values ('$name', '$email', '$password', '$fullImageURL')";
    $result = $mysqli->query($query);

    if ($result) {
        $response['success'] = true;
        $response['message'] = "User successfully";
        echo json_encode($response);
        die();
    }

    $response['success'] = false;
    $response['message'] = "User not created: " . $mysqli->error;
    $response['result: '] = $result;

    echo json_encode($response);
    die();
} else if ($action == "login_user") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "Select * from users where email = '$email' and password = '$password'";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        $userRow = $result->fetch_assoc();

        $response['success'] = true;
        $response['message'] = "User logged in successfully";
        $response['data'] = $userRow;
    } else {

        header('HTTP/1.1 400 Not found');
        $response['success'] = false;
        $response['message'] = "Incorrect email and password: " . $mysqli->error;
    }

    echo json_encode($response);
    die();
} else if ($action == "get_user") {

    $user_Id = $_POST['user_Id'];

    $query = "Select * from users where id = $user_Id";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {

        $userRow = $result->fetch_assoc();

        $response['success'] = true;
        $response['message'] = "User feched successfully";
        $response['data'] = $userRow;
    } else {
        header('HTTP/1.1 400 Not found');
        $response['success'] = false;
        $response['message'] = "User feched failed: " . $mysqli->error;
    }

    echo json_encode($response);
    die();
}
