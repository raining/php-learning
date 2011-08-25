<script type="text/javascript" src="imagesize.js"></script>
<?php

if (isset($_GET['dir'])) {
    $dir = $_GET['dir'];
}

if (isset($_GET['create'])) {
    $create = $_GET['create'];
}

if (isset($_GET['gallery_dir'])) {
    $gallery_dir = $_GET['gallery_dir'];
    create_gallery($gallery_dir);
}

if (isset($_GET['hidden'])) {
    $hidden = $_GET['hidden'];
}

if (isset($_POST['g'])) {
    $dirAfterUpload = $_POST['g'];
}

if (isset($_GET['go'])) {
    $go = $_GET['go'];
    print "<a href='gallery.php'>На главную</a>";
}

if (isset($_REQUEST['imageUploader'])) {
    echo "$_FILES = " . print_r($_FILES, true) . "<br>";
    echo "$_POST = " . print_r($_POST, true) . "<br>";
}

function create_gallery($gallery_dir = "tmpdir")
{
    $fullpath = $_GET['hidden'] . $gallery_dir . "/";
    echo $fullpath;

    mkdir($fullpath, 0770);

    if (isset($_FILES['myFile'])) {
       $dirAfterUpload = $_POST['g'];
        copy($_FILES['myFile']['tmp_name'], "$dirAfterUpload");
    }
}

function print_tree($path = "./*")
{
    foreach (glob($path) as $file) {
        if (is_dir($file)) {
            $basename = basename($file);
            print "<p><a href='?dir=$file/' type='dir'>$basename</a></p>";
            print_tree($path = $file . "/*");
        }
    }
}

function print_images($dir = "/images/*")
{
    $images = glob($dir . "*.{jpg,png,jpeg,gif}", GLOB_BRACE);
    $count_images = count($images);
    if ($count_images == 0) {
        $basenamedir = basename($dir);
        print "Папка $basenamedir пуста. <a href='gallery.php?create=1&dir=$dir'> Создайте здесь свою галерею</a>!";
    }

    for ($i = 0, $k = 0; $i < ceil($count_images / 4); ++$i) {
        print "<tr>";
        for ($j = 0; $j < 4; ++$j, ++$k) {
            print "<td>";
            if ($k < $count_images) {
                $image = rawurldecode($images[$k]);
//                print "<a href='$image'><img src='$image' height='200' width='200'></a>";
                print "<img class='expando' border='0' src='$image' width='200' height='200'>";
            }
            print "</td>";
        }
        print "</tr>";
    }
}

?>
<html>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
    <title>Галерея изображений</title>
    <link rel="stylesheet" type="text/css" href="gallery.css">
</head>
<body link="#828282" alink="#D3E2F0" vlink="#D1D1D1">
<div align="center" id="header"><img src="images/gallery.gif" alt="Галерея" align="middle"></div>
<div id="sidebar">
    <div class="text">
        <?php print_tree("./*"); ?>
    </div>
</div>
<div id="content">
<?php
        if (isset($dir)) {
    ?>
    <table cellspacing="5">
        <?php print_images($dir); ?>
    </table>
    <?php

}
    if (isset($gallery_dir)) {
        ?>
        <div class='text'>Ваша папка с фотографиями успешно создана. Пришло время добавить немного картинок!</div>
        <p>
        <form action="gallery.php" method="post" enctype="multipart/form-data">
            <input type="file" multiple accept="image/*, image/jpeg" name="myFile">
            <input type="hidden" value="<?php print $gallery_dir; ?>" name="g">
            <p>
                <input type="submit" name="imageUploader" value="Отправить на сервер">
            </p>
        </form>
        <!--            <a href="gallery.php?dir=-->
            <?php //print $hidden . $gallery_dir; ?><!--">Просмотреть свою папку с картинками.</a>-->
        </p>
        <?php
    }

    if (isset($create) && $create == 1) {
        ?>
        <form action="gallery.php" method="get" name="formChooseDir">
            <div class="text">Введите имя папки:</div>
            <input type="text" name="gallery_dir" value="">
            <input type="hidden" value="<?php print $dir; ?>" name="hidden">
            <input type="submit" value="Отправить">
        </form>

        <?php
    }

    ?>
</div>
<div id="footer">&copy;<a href="mailto:rainxforum@gmail.com">Ekaterina Khurtina</a></div>
</body>
</html>
