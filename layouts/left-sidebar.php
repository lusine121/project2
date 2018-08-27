<?php
//include_once 'components/db_functions.php';
//$stmt = $conn->query("SELECT * FROM categories");
//$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
include_once "components/db.php";

$db = new Database('news_app', 'root', '', 'homework.local');
//echo "<pre>";print_r($db);die;
$table = 'categories';
$where = '';
$limit = '';
$order = '';
$where_mode = '';
$select_fields = '*';

$data = $db -> select($table, $where, $limit, $order, $where_mode, $select_fields) -> result();
//echo "<pre>";print_r($data);die;
?>
<div class="container">
    <div class="row">
        <div class="col-md-3 left">
            <h3 style="font-style: italic" class="cat">Categories</h3>
            <ul class="category_nav" id="sortable">
            <?php foreach ($data as $value):?>
                    <li><a href="category.php?id=<?= $value['id']


                        ?>"><?= $value['title']?></a> </li>
                <?php endforeach;?>
            </ul>
        </div>
