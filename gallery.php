<?php

$error_messages["empty_dir"] = "В галерее нет изображений.";
$error_messages["not_exist_dir"] = "Запрашиваемой галереи не существует!";
$error_messages["wrong_file_extension"] = "Неподдерживаемый тип закачиваемого файла!";
$error_messages["wrong_perm"] = "Отсутствует доступ к запрашиваемой галерее.";
$error_messages["exist_dir"] = "Каталог уже существует!";

$server_dir = substr($_SERVER['DOCUMENT_ROOT'], 0, 13);
//print "server_dir = $server_dir<br>";

if (isset($_GET['error'])) {
    $error = 0;
    for ($i = 1; $i < 101; $i++) {
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
    //    print "realpath = " . realpath($_GET['dir']) . "<br>";
    //    print "substr = " . substr(realpath($_GET['dir']), 0, 13) . "<br>";

    if (strnatcasecmp(substr(realpath($_GET['dir']), 0, 13), $server_dir) != 0) { //не равны
        //        print $error_messages['not_exist_dir'] ." или " . $error_messages['wrong_perm'];
        $dir = "./";
        Header("Location: gallery.php?error=1");
        exit();
    }
    if (strnatcasecmp(substr(realpath($_GET['dir']), 0, 13), $server_dir) == 0) {
        $dir = $_GET['dir'];
    }
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
            print "<p><a href='?dir=$file/' type='dir'>$basename</a></p>";
        }
    }
}

function print_images($dir = "/images/*")
{
    $images = glob(realpath($dir) . "/*.{jpg,png,jpeg,gif}", GLOB_BRACE);
    $count_images = count($images);
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

function generate_error($string = "error")
{
    print $string != "" ? "<p><div id='error'>$string</div></p>" : "";
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
    if (strnatcasecmp($error, 1) == 0) {
        generate_error($error_messages['not_exist_dir'] . " или " . $error_messages['wrong_perm']);
    } else {
        generate_error($error);
    }
    print_images("./*");
    if (isset($dir)) {
        ?>
        <table cellspacing="5">
            <?php print_images($dir); ?>
        </table>
        <?php

    }
    ?>
</div>
<div id="footer">&copy; <a href="mailto:rainxforum@gmail.com">Ekaterina Khurtina</a></div>
</body>
</html>
