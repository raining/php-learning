<?php
require_once "lib.php";

$gallery_root = remove_last_slash_from_path($_SERVER['DOCUMENT_ROOT']);
$col_len = 4;

//full path to gallery w.o. / ([server_dir] ?= [server_dir]/some/path)
function is_valid_dir($root_dir, $dir)
{
    $base_dir = substr($dir, 0, strlen($root_dir));
    return strnatcasecmp($root_dir, $base_dir) == 0;
}

//full path to gallery w.o. /  (user_dir ?= server_dir)
function is_root_dir($root_dir, $dir)
{
    return strnatcasecmp($root_dir, $dir) == 0;
}

//full path to gallery w.o. /
function create_navigation($path)
{
    $dirs = glob("$path/*", GLOB_ONLYDIR);

    $items = array();

    foreach ($dirs as $dir_name) {
        $items[basename($dir_name)] = realpath($dir_name);
    }

    return $items;
}

//full path to gallery w.o. /
function create_images_list($path)
{
    return glob("$path/*.{jpg,JPG,png,jpeg,gif}", GLOB_BRACE);
}

function create_gallery($dir)
{
    return mkdir($dir, 0770);
}

//variables of possible errors
$empty_dir = "В галерее нет изображений.";
$wrong_file_extension = "Неподдерживаемый тип закачиваемого файла!";
$exist_dir = "Каталог уже существует!";
$not_exist_dir = "Запрашиваемой галереи не существует или отсутствует доступ к указанной галерее.";
$wrong_creating_dir = "Невозможно создать новую галерею.<br>
                        Возможные причины:<br>
                        -отсутствуют необходимые права на запись,<br>
                        - галерея с указанным именем уже существует.";
$wrong_name = "Вы указали неверное имя.";
$unknown_error = "Неизвестный тип ошибки.";

if (isset($_GET['gallery'])) {
    $gallery_path = remove_last_slash_from_path($_GET['gallery']); // f.e. /my_gallery/family
    $real_gallery_path = realpath($gallery_root . $gallery_path); // /var/www/html/my_gallery

    if (!is_valid_dir($gallery_root, $real_gallery_path)) {
        $real_gallery_path = $gallery_root;
        $error_messages[] = $not_exist_dir;
    }
}
else {
    $real_gallery_path = $gallery_root;
}

if (isset($_POST['gallery_upload'])) {
    $gallery_upload = $_POST['gallery_upload'];
    $mod_gallery_upload = realpath("$gallery_root/$gallery_upload");
    $filename = $_FILES['myfile']['name'];
    copy($_FILES['myfile']['tmp_name'], "$mod_gallery_upload/$filename");
    if (empty($gallery_upload)) {
        Header("Location: gallery.php");
        exit;
    }
    else {
        Header("Location: gallery.php?gallery=$gallery_upload");
        exit;
    }

}

if (isset($_GET['new_gallery'])) {
    $new_gallery_path = remove_last_slash_from_path($_GET['new_gallery']); // f.e. /my_gallery/family
    $new_real_gallery_path = "$real_gallery_path/$new_gallery_path"; // /var/www/html/my_gallery

    print $new_real_gallery_path;
    if (!is_valid_dir($gallery_root, $new_real_gallery_path)) {
        $error_messages[] = $wrong_name;
    }


    if (!create_gallery($new_real_gallery_path)) {
        $error_messages[] = $wrong_creating_dir;
    }
}

if (isset($_GET['create'])) {
    $create = $_GET['create'];
    if (!strnatcasecmp($create, 1)) {
        $create = 1;
    }
}

$navigation = create_navigation($real_gallery_path);
$images = create_images_list($real_gallery_path);

$is_dir_empty = count($images) == 0;

if ($is_dir_empty) {
    $error_messages[] = $empty_dir;
}
?>

<html>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
    <title>Галерея изображений</title>
    <link rel="stylesheet" type="text/css" href="gallery.css">
</head>
<body>
<div align="center" id="header"><a href="gallery.php"><img src="images/gallery.gif" alt="Галерея" align="middle"></a>
</div>
<div id="sidebar">
    <div class="text">
<?php
    if (!is_root_dir($gallery_root, $real_gallery_path)) {
    $parent = unrealpath($gallery_root, dirname($real_gallery_path));

    if (empty($parent)) {
        print "<a class='home' href='gallery.php'>Выше</a><br>";
    }
    else {
        print "<a class='home' href='gallery.php?gallery=$parent'>Выше</a><br>"; // todo: fix it
    }
}

    for (reset($navigation); $name = key($navigation); next($navigation)) {
        //$navigation = [key = basename(dir); value = realpath(dir);]
        $gallery_path = unrealpath($gallery_root, $navigation[$name]);
        print "<p><a href='gallery.php?gallery=$gallery_path'>$name</a></p>";
    }

    ?>
    </div>

    <p>

    <p>

    <p></p></p></p>
    <div class="text" id=menu>
        <?php if (!isset($_GET['gallery'])) { ?>
        <a href="gallery.php?create=1">Создать новую галерею</a><br>
        <?php
    }
    else {
        ?>
        <a href="gallery.php?gallery=<?php print unrealpath($gallery_root, $real_gallery_path); ?>&create=1">Создать новую галерею</a><br>
        <?php }?>
    </div>
</div>
<div id="content">
<?php
    //print error array
    foreach ($error_messages as $error_message) {
        ?>
        <div id='error'><?php print $error_message; ?></div><br><?php

    }
    ?>
    <table cellspacing="5">
<?php
        if (is_root_dir($gallery_root, $real_gallery_path)) {
    print "<div id='welcome-message'>Добро пожаловать в галерею!
    Здесь можно просмотреть изображения, добавить свои галереи и делиться ими в соцсетях!</div>";
}
    for ($i = 0, $name = 0; $i < ceil(count($images) / $col_len); ++$i) { // todo: fix it
        print "<tr>";
        for ($j = 0; $j < $col_len; ++$j, ++$name) {
            print "<td>";
            if ($name < count($images)) {
                $image = substr(rawurldecode($images[$name]), 14, strlen(rawurldecode($images[$name])) - 13); // todo: fix it
                print "<a href='$image'><img src='$image' height='200' width='200'></a>";
            }
            print "</td>";
        }
        print "</tr>";
    }

    print "<div class='text'>Желаете добавить изображения?</div>"; ?>
    <form method='post' action='gallery.php' enctype='multipart/form-data'>
        <input type='file' name='myfile'>
        <input type="hidden" name="gallery_upload"
               value="<?php print unrealpath($gallery_root, $real_gallery_path); ?>">
        <input type='submit' name='upload' value='Закачать'>
    </form>

    <p>

    <p></p></p><br>

<?php
    if (isset($create)) {
    ?>
    <form method="get" action="gallery.php">
        <div class="text">Имя новой галереи:</div>
        <input type=text name="new_gallery">
        <input type="hidden" name="gallery"
               value="<?php print unrealpath($gallery_root, $real_gallery_path); ?>">

        <input type="submit" name="ok" value="Ок">
    </form>
    <?php } ?>
    </table>
</div>
<div id="footer">&copy; <a href="mailto:rainxforum@gmail.com">Ekaterina Khurtina</a></div>
</body>
</html>
