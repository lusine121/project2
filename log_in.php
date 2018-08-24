<?php
//require_once 'components/db_functions.php';
include_once "components/db.php";
include_once 'layouts/header.php';
include_once 'cookies_sessions/session_on.php';
include_once 'valid/admin_validate.php';
?>

<?php

if(check_session()){
    header("Location:index.php");
}

$sign_email = $_POST['sign_email'];
$sign_password = sha1(($_POST['sign_password']));
$remember_me = $_POST['checkbox'];
$errors = [];

if (isset($_POST["singlebutton"])) {
    if (!required($sign_email) || !required($_POST['sign_password'])) {
        $errors[] = "Please fill all required fields.";

    }

    if (!empty($sign_email)) {
        if (!valid_email($sign_email)) {
            $errors[] = "You must enter valid email address.";
        }
    }else{

    //if( count($errors )===0) {
        //$query = $conn->query("Select * from registr_s where email = '$sign_email' and password ='$sign_password' ");
        //$count = $query->rowcount();
        //$row = $query->fetch();


$db = new Database('news_app', 'root', '', 'homework.local');
$table = 'registr_s';
$where = '';
$limit = '';
$order = '';
$where_mode = '';
$select_fields = '*';

$stmt = $db -> select($table, $where = array('email' => $sign_email, 'password' => $sign_password), $limit, $order, $where_mode, $select_fields);
    }
        //if ($count > 0) {
            //session_start();
            //$_SESSION['id'] = $row['id'];
            //$_SESSION['name'] = $row['f_name'];

            if ($stmt > 0) {
            session_start();
            $_SESSION['id'] = $stmt['id'];
            $_SESSION['name'] = $stmt['f_name'];

            header('location:welcome.php');
        }
        else{
            $warn = "Please insert correct data.";
        }

        if ($_POST["checkbox"] == '1' || $_POST["checkbox"] == 'on') {
            $hour = time() + 3600 * 24 * 30;
            setcookie('sign_email', $sign_email, $hour);

        }

    }

?>

<?php
include_once 'layouts/left-sidebar.php';
?>

<div class="col-md-9 right">
    <div class="container">
        <form method="post" action="log_in.php">
            <!-- Form Name -->
            <h2 class="m_top about_info">Log In</h2>

            <!-- Email input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="E-mail">E-mail:</label>
                <div class="col-md-6">
                    <input value="<?= trim($sign_email) ?>" name="sign_email" class="form-control input-md" id="E-mail"
                           type="text" placeholder="E-mail">

                </div>
            </div>


            <!-- Password input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="passwordinput">Password:</label>
                <div class="col-md-6">
                    <input value="<?= trim($_POST['sign_password']) ?>" name="sign_password"
                           class="form-control input-md" id="passwordinput" type="password"
                           placeholder="Password">

                </div>
            </div>


            <div class="form-group">
                <label class="col-md-4 control-label" for="checkbox">Remember me</label>
                <div class="col-md-6">
                    <input name="checkbox" class=" input-md" id="checkbox" type="checkbox">
                </div>
            </div>


            <!-- Button -->
            <div class="form-group">
                <div class="col-md-6">
                    <button name="singlebutton" class="btn btn-primary " id="singlebutton">Log in</button>
                </div>
            </div>
        </form>

        <div class="warning">
            <?php foreach($errors as $error): ?>
            <?= $error ;?>
            <div><?php endforeach;?></div>

        </div>
    </div>
</div>

</div>
</div>

<?php 
include_once 'layouts/footer.php'; 
?>



