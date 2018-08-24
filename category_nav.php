<?php
require_once 'components/db_functions.php';
require_once 'layouts/header.php';
?>


<?php

$sql_count = "select cat.id,cat.title, COUNT(*) as count from categories as cat left join news as new on cat.id = new.category_id GROUP BY cat.title  ORDER BY count ASC";
$stmt_count = $conn->query($sql_count);
$counts = $stmt_count->fetchAll();
//echo "<pre>";
//var_dump($counts);
//die();
//<div class="row "> h2 class ic heto
?>


<?php
require_once 'layouts/left-sidebar.php';
?>
    <div class="col-md-9 right ">
        <h2 class="about_info"> Count of Categories</h2>
        
            <?php foreach ($counts as $count): ?>
                <div class="col-md-3   col-xs-3 col-sm-3 ">
                    <div class="cat_nav">
                        <a href="category.php?id=<?php echo $count['id']; ?>"><?= $count['title'] . " "; ?><?= $count['count']; ?></a>
                    </div>
                </div>
            <?php endforeach; ?>
            <div>
            </div>

        </div>
    </div>
<?php require_once 'layouts/footer.php'; ?>