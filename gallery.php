<?php

$error_messages["empty_dir"] = "В галерее нет изображений.";
$error_messages["not_exist_dir"] = "Запрашиваемого каталога не существует!";
$error_messages["wrong_file_extension"] = "Неподдерживаемый тип закачиваемого файла!";
$error_messages["wrong_perm"] = "Отсутствует доступ к запрашиваемой галерее.";
$error_messages["exist_dir"] = "Каталог уже существует!";

$server_dir = $_SERVER['DOCUMENT_ROOT'];

if (isset($_GET['dir'])) {
    print realpath($_GET['dir']);
    if (!file_exists($_GET['dir'])) {
        //$error_messages["not_exist_dir"];
        $dir = "./";
    } else {
        $dir = $_GET['dir'];
    }
}

if (isset($_GET['image'])) {}

if (isset($_GET['create'])) {
    if ($_GET['create'] == 1) {
        $create = $_GET['create'];
    }
}

if (isset($_GET['gallery_dir'])) {
    $gallery_dir = $_GET['gallery_dir'];
    create_gallery($gallery_dir);
}


if (isset($_REQUEST['imageUploader'])) {
    echo "$_FILES = " . print_r($_FILES, true) . "<br>";
    echo "$_POST = " . print_r($_POST, true) . "<br>";
}

function create_gallery($gallery_dir = "tmpdir")
{
    if (isset($_GET['hidden'])) {
        $hidden = $_GET['hidden'];
        $fullpath = "$hidden . $gallery_dir . /";
        echo $fullpath;
        mkdir($fullpath, 0770);
    }

}

function print_tree($path = "./*")
{
    foreach (glob($path) as $file) {
        if (is_dir($file)) {
            $basename = basename($file);
            $real = realpath($file);
            print "<p><a href='?dir=$file/' type='dir'>$basename | realpath=$real</a></p>";
            //            print_tree($path = $file . "/*");
        }
    }
}

function print_images($dir = "/images/*")
{
    $images = glob($dir . "*.{jpg,png,jpeg,gif}", GLOB_BRACE);
    $count_images = count($images);
    if ($count_images == 0) {
        $basenamedir = basename($dir);
        print "В папке $basenamedir нет изображений. <a href='gallery.php?create=1&dir=$dir'> Добавить картинок в галерею</a>.";
    }

    for ($i = 0, $k = 0; $i < ceil($count_images / 4); ++$i) {
        print "<tr>";
        for ($j = 0; $j < 4; ++$j, ++$k) {
            print "<td>";
            if ($k < $count_images) {
                $image = rawurldecode($images[$k]);
                print "<a href='$image'><img src='$image' height='200' width='200'></a>";
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
<body>
<div align="center" id="header"><a href="gallery.php"><img src="images/gallery.gif" alt="Галерея" align="middle"></a></div>
<div id="sidebar">
    <div class="text">
<?php

    if (isset($dir) && $dir != "./") {
            $parent = dirname($dir);
            print "<a class='home' href='gallery.php?dir=$parent/'>Назад</a><br>";
        print_tree($dir . "*");
    } else {
        print_tree("./*");
    }
    ?>
    </div>
</div>
<div id="content">
<?php

    if ($_SERVER['QUERY_STRING'] == "") {
        print_images("./*");
    }
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
            <input type="file" accept="image/*, image/jpeg" name="myFile">
            <input type="hidden" value="<?php print $gallery_dir; ?>" name="uploaddir">

            <p>
                <input type="submit" name="imageUploader" value="Отправить на сервер">
            </p>
        </form>
        </p>
        <?php

    }

    if (isset($create) && ($create == 1 || $create == 2)) {
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
<div id="footer">&copy; <a href="mailto:rainxforum@gmail.com">Ekaterina Khurtina</a></div>
</body>
</html>
