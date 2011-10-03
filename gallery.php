<?php

//massiv of possible errors
$error_messages["empty_dir"] = "В галерее нет изображений."; //1
$error_messages["wrong_file_extension"] = "Неподдерживаемый тип закачиваемого файла!"; //3
$error_messages["exist_dir"] = "Каталог уже существует!"; //5
//todo: comlete massiv of possible errors (f.e. 10+ elements)

$server_dir = substr($_SERVER['DOCUMENT_ROOT'], 0, 13); //if document_root=/var/www/html  (not flexible!)
//todo: for any DOCUMENT_ROOT directory

if (isset($_GET['error'])) {
    $error = 0;
    for ($i = 1; $i < 6; $i++) { //for 5 types of errors yet
        if (strnatcasecmp($_GET['error'], "$i") == 0) {
            $error = $_GET['error'];
            break;
        }
    }
    if ($error == 0) {
        unset($_GET['error']);
    }
}

if (isset($_GET['dir'])) {

    // if it's out of range of document_root dir, f.e. /var,/var/www, /home,./../../../ etc
    if (strnatcasecmp(substr(realpath($_GET['dir']), 0, 13), $server_dir) != 0) {
        $dir = "./";
        Header("Location: gallery.php?error=1");
        exit;
    }
    //if this is a range of document_root dir,so it's ok
    if (strnatcasecmp(substr(realpath($_GET['dir']), 0, 13), $server_dir) == 0) {
        $dir = $_GET['dir'];
    }
}
else {
    $dir = "./";
    $is_dir_defined = 0;
}

function create_gallery($gallery_dir = "tmpdir")
{
    //todo: fix
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
            print "<p><a href='?dir=$file/' type='dir'>$basename</a></p>";
        }
    }
}

function print_images($dir = "/images/*")
{
    $images = glob(realpath($dir) . "/*.{jpg,png,jpeg,gif}", GLOB_BRACE);
    $count_images = count($images);
    print "count_images = $count_images";
    if ($count_images == 0) {
        ;
    }

    for ($i = 0, $k = 0; $i < ceil($count_images / 4); ++$i) {
        print "<tr>";
        for ($j = 0; $j < 4; ++$j, ++$k) {
            print "<td>";
            if ($k < $count_images) {
                $image = substr(rawurldecode($images[$k]), 14, strlen(rawurldecode($images[$k])) - 13);
                print "<a href='$image'><img src='$image' height='200' width='200'></a>";
            }
            print "</td>";
        }
        print "</tr>";
    }
}

function generate_error($error_code = 1)
{
    switch ($error_code) {
        case'1' :
            {
            print "Запрашиваемой галереи не существует или отсутствует доступ к указанной галерее.";
            break;
            }
        case '2' :
            {
            }
        case '3':
            {

            }
        case '4':
            {

            }
        case '5':
            {

            }
        default:
            {
            print "Неизвестный тип ошибки";
            break;
            }
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
<div align="center" id="header"><a href="gallery.php"><img src="images/gallery.gif" alt="Галерея" align="middle"></a>
</div>
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
    if ($error > 0) {
    ?>
        <div id=error><?php generate_error($error); ?></div>
    <?php

    }
    ?>
    <table cellspacing="5">
        <?php
        if ($is_dir_defined == 0) {
            print "<div id='welcome-message'>Добро пожаловать в галерею! Здесь можно просмотреть изображения, добавить свои галереи и делиться ими в соцсетях!</div>";
        }
        print_images($dir); ?>
    </table>
</div>
<div id="footer">&copy; <a href="mailto:rainxforum@gmail.com">Ekaterina Khurtina</a></div>
</body>
</html>
