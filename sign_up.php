<?php


//error_reporting(E_ALL);
include_once "components/db_functions.php";
//include_once "components/db.php";
include_once 'valid/admin_validate.php';
include_once 'cookies_sessions/session_on.php';
include_once 'components/countries.php';
if(check_session()){
    header("Location:index.php");
}
//print_r($_POST);


//settings
ini_set('upload_max_filesize', '1024M');//changed file_upload size
ini_set('max_input_time', 300);
ini_set('precision', 14);

//$_POST VARIABLES

$f_name = $_POST['name'];
$l_name = $_POST['l_name'];
$email = $_POST['e_mail'];
$password = $_POST['password'];
$conf_password = $_POST['conf_password'];
$select_countries = $_POST['select_country'];
$registr_date = date('Y-m-d');


//$_FILES VARIABLES
$profile = $_FILES['profile'];
$target_direct = "uploads/profiles/";
$newProfileName = explode(".", basename($_FILES["profile"]["name"]));
$newProfileName[0] = time();
$newProfileName = implode(".", $newProfileName);
$target_file = $target_direct . $newProfileName;////anun poxel
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


//nayel php date funkcianer@
/// phpinfo() usumnasirel, error noticners miacnel
///E_notice@
/// table-i anun@ lini users
///ckeditor for text designs , integration in forms, slider and gallerys
//////resize php gradaran or crope usumnasirel
$errors_arr = [];
if (isset($_POST["submit"])) {
    if (!required($f_name) || !required($l_name) || !required($email) || !required($password) || !required($conf_password) || !required($select_countries) || !imageRequired($_FILES)) {
        $errors_arr[] = "Please fill all required fields.";
    }


    if (!is_text($f_name) || !is_text($l_name)) {
        $errors_arr[] = "First Name and Last Name must contain only letters.";
    }


    if (!is_equal($password, $conf_password)) {
        $errors_arr[] = "Password and Confirm password must be equal.";
    }

    if (!empty($email)) {
        if (!valid_email($email)) {
            $errors_arr[] = "You must enter valid email address.";
        }
    }

    // Check if image file is a actual image or fake image
    if (($_FILES['profile']['size'] > 0)) {
        $check = getimagesize($_FILES["profile"]["tmp_name"]);
        if ($check == false) {
            $errors_arr[] = "File is not an image.";
            $uploadOk = 0;
        }
    }

// Check if file already exists
    if (file_exists($target_file)) {
        $errors_arr[] = "Sorry, file already exists.";
        $uploadOk = 0;
    }


    //Check file size
    if ($_FILES["profile"]["size"] > 800 * 1024) {
        $errors_arr[] = "Sorry, your file is too large.";
        $uploadOk = 0;
    }
// Allow certain file formats
    if (($_FILES['profile']['size'] > 0)) {
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            $errors_arr[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
    }


    ///checking if there is email repeat
    $query = $conn->query("Select * from registr_s where email = '$email' ");
    $count = $query->rowcount();
    $row = $query->fetch();

    //$db = new Database('news_app', 'root', '', 'homework.local');
      //$table = 'registr_s';
      //$where = '';
      //$limit = '';
      //$order = '';
      //$where_mode = '';
      //$select_fields = '*';

      //$stmt = $db -> select($table, $where = array('email' => $email),$limit, $order, $where_mode, $select_fields);

      //if($stmt > 0){
    if ($count > 0) {
        $errors_arr[] = "Your email address is already in use.";
    }

    if (count($errors_arr) === 0 && $uploadOk !== 0) {


        try {
//            echo "Connected successfully" . "<br>";


            $sql = "Insert into  registr_s(f_name, l_name, email, password, profile_path, country_id, registr_date) values('$f_name', '$l_name', '$email', sha1('$password'), '$newProfileName', '$select_countries', '$registr_date' )";

//$db = new Database('news_app', 'root', '', 'homework.local');
//$db -> insert('registr_s', $fields = array('f_name' => $f_name, 'l_name' => $l_name, 'email' => $email, 'password' => sha1($password), 'profile_path' => $newProfileName, 'country_id' => $select_countries, 'registr_date' => $registr_date));

            if (move_uploaded_file($_FILES["profile"]["tmp_name"], $target_file)) {
                echo "The file " . $newProfileName . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }

            //if ($conn->exec($db)) {
            if ($conn->exec($sql)) {
                echo "New record created successfully";

                $query = $conn->query("Select * from registr_s where email = '$email' and password ='" . sha1($password) . "' ");
                $count = $query->rowcount();
                $row = $query->fetch();

                //$db = new Database('news_app', 'root', '', 'homework.local');
                //$table = 'registr_s';
                //$where = '';
                //$limit = '';
                //$order = '';
                //$where_mode = '';
                //$select_fields = '*';

                //$stmt = $db -> select($table, $where = array('email' => $email, 'password' => sha1($password)), $limit, $order, $where_mode, $select_fields);

                    //if ($stmt > 0) {
                        //session_start();
                    //$_SESSION['id'] = $stmt['id'];
                    //$_SESSION['name'] = $stmt['f_name'];

                    if ($count > 0) {
                    session_start();
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['name'] = $row['f_name'];
                    
                    header('location:welcome.php');
                    exit();
                }



            } else {
                echo "Error: ";
            }


        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

}

?>

<?php
include_once 'layouts/header.php';
?>


<?php
include_once 'layouts/left-sidebar.php';
?>
<div class="col-md-9 right">

    <div class="container">
        <form method="post" action="sign_up.php" enctype="multipart/form-data">
            <!-- Form Name -->
            <h2 class="m_top about_info">Registration Page</h2>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="textinput">Name:</label>
                <div class="col-md-6">
                    <input value="<?= trim($f_name) ?>" name="name" class="form-control input-md" id="textinput"
                           type="text" placeholder="Name">

                </div>
            </div>

            <!-- l_name input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="email">Last Name:</label>
                <div class="col-md-6">
                    <input value="<?= trim($l_name) ?>" name="l_name" class="form-control input-md" id="email"
                           type="text"
                           placeholder="Last Name">

                </div>
            </div>

            <!-- Email input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="E-mail">E-mail:</label>
                <div class="col-md-6">
                    <input value="<?= trim($email) ?>" name="e_mail" class="form-control input-md" id="E-mail"
                           type="text" placeholder="E-mail">

                </div>
            </div>

            <!-- Password input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="passwordinput">Password:</label>
                <div class="col-md-6">
                    <input value="<?= trim($password) ?>" name="password" class="form-control input-md"
                           id="passwordinput" type="password"
                           placeholder="Password">

                </div>
            </div>

            <!-- Confpassword input -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="confpassword">Confirm Password</label>
                <div class="col-md-6">
                    <input value="<?= trim($conf_password) ?>" name="conf_password" class="form-control input-md"
                           id="confpassword" type="password"
                           placeholder="Confirm Password">
                </div>
            </div>

            <!--image upload -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="profile">Profile image</label>
                <div class="col-md-6">
                    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_SIZE; ?>"/>
                    <p id="download_image"><input type="file" id="profile" class="form-control-file"
                                                  value="<?= $profile ?>" name="profile">
                    </p>

                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label" for="country">Choose your country</label>
                <select class="form-control sel_margin_bottom col-md-6" name="select_country" id="country">
                    <?php
                    foreach ($countries as $key => $country) {

                        if ($key < 1) continue;

                        echo '<option value="' . $key . '">' . $country . '</option>';
                    }
                    ?>


                </select>
            </div>

            <!-- Button -->
            <div class="form-group">
                <div class="col-md-6">
                    <button name="submit" class="btn btn-primary" id="singlebutton" type="submit">Registration</button>
                </div>
            </div>

        </form>
        <div class="warning">
            <?php foreach ($errors_arr as $value): ?>
                <div><?= $value; ?></div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</div>
</div>

<?php 
include_once 'layouts/footer.php'; 
?>