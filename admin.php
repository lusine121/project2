<?php
include_once 'components/db_functions.php';
include_once 'valid/admin_validate.php';
include_once 'layouts/header.php';
include_once 'cookies_sessions/session_on.php';
//include_once "components/db.php";
if (!check_session()) {
    header("Location:index.php");
}
?>


<?php
//print_r($_POST);
ini_set('upload_max_filesize', '1024M'); //changed file_upload size
ini_set('max_input_time', 300);
ini_set('precision', 14);


$title = $_POST['title'];
$description = $_POST['description'];
$content = $_POST['article'];
$date = date('Y-m-d');
$select = $_POST['select'];
$image = $_FILES['image'];
//$autor_id = $_SESSION['id'];
$news_error = [];


$target_dir = "uploads/";
$newname = explode(".", basename($_FILES["image"]["name"]));
$newname[0] = time();
$newname = implode(".", $newname);
$target_file = $target_dir . $newname;////anun poxel
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


if (isset($_POST["save"])) {
    if (!required($_POST['title']) || !required($_POST['description']) || !required($_POST['article']) || !imageRequired($_FILES) || !isset($_POST['select'])) {
        $news_error[] = "All fields are nessesary";

    }
// Check if image file is a actual image or fake image
    if (($_FILES['image']['size'] > 0)) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check == false) {
            $news_error[] = "File is not an image.";
            $uploadOk = 0;

        }
    }

// Check if file already exists
    if (file_exists($target_file)) {
        $news_error[] = "Sorry, file already exists.";
        $uploadOk = 0;

    }


//Check file size
    if ($_FILES["image"]["size"] > 800 * 1024) {
        $news_error[] = "Sorry, your file is too large.";
        $uploadOk = 0;

    }
// Allow certain file formats
    if (($_FILES['image']['size'] > 0)) {
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            $news_error[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
    }


    if (count($news_error)=== 0 && $uploadOk !== 0) {
       var_dump(count($news_error));
       var_dump($uploadOk);
        try {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                echo "The file " . $newname . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
           $sql_news_creating = "Insert into  news(title, description, content, date_of_creating, image_path,category_id) values('" . $title . "' , '" . $description . "', '" . $content . " ', '" . $date . " ', '" . $newname . " ', '" . $select . " ')";
           //$db = new Database('news_app', 'root', '', 'homework.local');
           //$db -> insert('news', $fields = array('title' => '$title', 'description' => '$description', 'content' => '$content', 'date_of_creating' => '$date', 'image_path' => '$newname', 'category_id' => '$select'));

            if ($conn->exec($$sql_news_creating)) {
               //if  ($conn->exec($db)){
                echo "New record created successfully";
                header("Location:index.php");
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
include_once 'layouts/left-sidebar.php';
?>

<div class="col-md-9 right  ">
    <div class="col-md-12 ">
        <form action="admin.php" method="post" enctype="multipart/form-data">
            <!-- <h3><a  class= "admin_info" href = welcome.php>For going back, just click here.</a></h3>-->
            <header><h2 class="about_info">Create News</h2></header>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-12">
                        <p><label for="title">Title</label></p>
                        <p><input type="text" id="title" class="form-control" placeholder="Title" name="title"></p>
                    </div>
                    <div class="col-md-12">
                        <p><label for="description">Description</label></p>
                        <p><textarea id="description" class="form-control" placeholder="Description"
                                     name="description"></textarea></p>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-12">
                        <p><label for="content">Article</label></p>
                        <p><textarea id="content" class="form-control content" placeholder="Content ... "
                                     name="article"></textarea>
                        </p>
                        <script>

                            CKEDITOR.replace('content');

                        </script>

                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <p><input type="file" id="image" class="form-control-file" value="Choose image" name="image"></p>
                <p><label for="category">Category</label></p>
                <select class="form-control sel_margin_bottom" name="select" id="category">
                    <?php
                    $categories = ["", "Art", "Sport", "Government", "Environmental", "Politics", "Weather", "Universe", "Medicine"];
                    foreach ($categories as $key => $val) {
                        if ($key < 1) continue;

                        echo '<option value="' . $key . '">' . $val . '</option>';

                    }
                    echo '</select>';


                    ?>

                </select>
            </div>


            <div class="col-md-4">
                <p>
                    <button type="submit" id="button" class="btn btn-primary btn-md" value="Save" name="save">Save
                    </button>
                </p>
            </div>
        <div class="warning">
            <?php foreach($news_error as $new_error):?>
                <div><?=$new_error ?></div>
            <?php endforeach; ?>
        </div>
        </form>
    </div>
</div>


</div>
</div>

<?php require_once 'layouts/footer.php'; ?>
<?php   ?>