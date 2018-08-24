<?php
error_reporting(E_WARNING);
include_once "cookies_sessions/session_on.php";
include_once 'components/db_functions.php';
//include_once "components/db.php";

if(isset ($_SESSION['id'])) {
    $user_id = $_SESSION['id']; 
}else{
    $user_id = "";

}


$stmt = $conn->query("select * from registr_s where id = '$user_id'"  );
$data = $stmt->fetch(PDO::FETCH_ASSOC);
//var_dump($data );
//$db = new Database('news_app', 'root', '', 'homework.local');
//echo "<pre>";print_r($db);die;

//$table = 'registr_s';
//$where = '';
//$limit = '';
//$order = '';
//$where_mode = '';
//$select_fields = 'id';

//$stmt = $db -> select($table, $where, $limit, $order, $where_mode, $select_fields) -> result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>News universe</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/fontawesome.css">
    <script src="ckeditor/ckeditor.js"></script>
</head>
<body>
<div class="container-fluid ">
    <div class="container header ">
        <div class="navigation">
            <nav class="row ">
                <div class="col-md-3  col-xs-3 nav_bottom left_header" id="logo"><a href="index.php"><h2 >Ne<i>W</i>s Corner</h2></a></div>
                <div class="col-md-9 col-xs-9 nav_bottom right_header">
                    <ul>
                        <li class="nav"><a class="nav_href" href="index.php">Home</a></li>
                        <li class="nav"><a class="nav_href" href="about_us.php">About Us</a></li>
                        <li class="nav"><a class="nav_href" href="contact.php">Contact Us</a></li>
                        <li class="nav"><a class="nav_href" href="category_nav.php">Categories</a></li>
                        

                        <?php
                        if (check_session()): ?>

                            <li class="nav"><a class="nav_href" href="log_out.php">Log out</a></li>
                            <li class="nav"><a class="nav_href" href="admin.php">Creat News</a></li>
                            <li class="nav"><a class="nav_href" href="welcome.php" title = "Profile">
                            
                                    <?= $data['f_name']?>
                                    <img  class="profile_image"  src="uploads/profiles/<?php echo $data['profile_path']  ?>" alt="">
                                </a></li>
                        <?php else: ?>
                            <li class="nav"><a class="nav_href" href="sign_up.php">Sign up</a></li>
                            <li class="nav" ><a class="nav_href" href="log_in.php">Log in</a></li>

                        <?php endif; ?>

                    </ul>
                </div>
                <div class="container header_background_image"></div>
            </nav>

        </div>
    </div>