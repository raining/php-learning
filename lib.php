<?php
function remove_last_slash_from_path($gallery_path)
{
    $path_len = strlen($gallery_path);
    $modified_gallery_path = substr($gallery_path, $path_len - 1, $path_len);
    $slash = "/";
    if (strnatcasecmp($modified_gallery_path, "/") == 0) {
        return substr($gallery_path, 0, strlen($gallery_path) - 1);
    }
    else {
        return $gallery_path;
    }
}

//returns str, reversed to realpath(str), f.e.
//realpath("[/some/path/]to/dir") = /some/path/to/dir
//unrealpath("[/some/path/", "/to/dir/") = to/dir
//dirs w.o. /
function unrealpath($root_dir, $dir) {
    return substr($dir, strlen($root_dir));
}

?>
 
