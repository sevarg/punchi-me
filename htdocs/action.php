<?php
/**
 * Punchi.me
 * Process
 * @author OnlineCreation - Raphaël
 * @licence GNU GPL v3.0
 * @link http://www.onlinecreation.pro Authors' website
 * @link http://www.punchi.me Demo
 */
require 'config.php';
require 'class/class.upload.php';

// Making a Zip archive
$zip = new ZipArchive();
$zipfile = time() . rand(10, 99) . ".zip";
$filename = TMP_DIR . DIRECTORY_SEPARATOR . $zipfile;
$size = 0;
if ($zip->open($filename, ZipArchive::CREATE) !== TRUE) {
    exit('cannot create zip archive');
}

// For each uploaded file
for ($i = 0; $i < $_POST['uploader_count']; $i++) {
    
    // Size control
    $size += filesize(TMP_DIR . DIRECTORY_SEPARATOR . $_POST['uploader_' . $i . '_tmpname']);
    if ($size > (MAX_QUEUE_SIZE_MB * 1024 * 1024)) {
        break;
    }
    
    // upload object
    $handle[$i] = new upload(TMP_DIR . DIRECTORY_SEPARATOR . $_POST['uploader_' . $i . '_tmpname']);
    $handle[$i]->mime_check = true;
    $handle[$i]->allowed = array('image/*');
    $handle[$i]->file_new_name_body = $_POST['uploader_' . $i . '_name'];
    $handle[$i]->file_name_body_add = '.edited';
    
    // convert into jpeg or png
    switch ($_POST['format']) {
        case 'JPEG100':
            $handle[$i]->jpeg_quality = 100;
            $handle[$i]->image_convert = 'jpeg';
            break;
        case 'JPEG85' :
            $handle[$i]->jpeg_quality = 85;
            $handle[$i]->image_convert = 'jpeg';
            break;
        case 'JPEG50' :
            $handle[$i]->jpeg_quality = 50;
            $handle[$i]->image_convert = 'jpeg';
            break;
        case 'PNG' :
            $handle[$i]->image_convert = 'png';
            $handle[$i]->png_compression = 9;
            break;
    }

    // changing size
    switch ($_POST['ratio']) {
        case "ratio-width":
            $handle[$i]->image_resize = true;
            $handle[$i]->image_ratio_y = true;
            if ($_POST['width'] < RESIZE_MAX_WIDTH) {
                $handle[$i]->image_x = $_POST['width'];
            } else {
                $handle[$i]->image_x = RESIZE_MAX_WIDTH;
            }
            break;
        case "ratio-height":
            $handle[$i]->image_resize = true;
            $handle[$i]->image_ratio_x = true;
            if ($_POST['height'] < RESIZE_MAX_HEIGHT) {
                $handle[$i]->image_y = $_POST['height'];
            } else {
                $handle[$i]->image_y = RESIZE_MAX_HEIGHT;
            }
            break;
        case "crop":
            $handle[$i]->image_resize = true;
            $handle[$i]->image_ratio_crop = true;
            if ($_POST['width'] < RESIZE_MAX_WIDTH) {
                $handle[$i]->image_x = $_POST['width'];
            } else {
                $handle[$i]->image_x = RESIZE_MAX_WIDTH;
            }
            if ($_POST['height'] < RESIZE_MAX_HEIGHT) {
                $handle[$i]->image_y = $_POST['height'];
            } else {
                $handle[$i]->image_y = RESIZE_MAX_HEIGHT;
            }
            break;
        case "noratio":
            $handle[$i]->image_resize = true;
            if ($_POST['width'] < RESIZE_MAX_WIDTH) {
                $handle[$i]->image_x = $_POST['width'];
            } else {
                $handle[$i]->image_x = RESIZE_MAX_WIDTH;
            }
            if ($_POST['height'] < RESIZE_MAX_HEIGHT) {
                $handle[$i]->image_y = $_POST['height'];
            } else {
                $handle[$i]->image_y = RESIZE_MAX_HEIGHT;
            }
            break;
    }
    
    // rotate
    if(in_array($_POST['rotation'],array(90,180,270))) {
        $handle[$i]->image_rotate = $_POST['rotation'];
    }
    
    // flip
    if(in_array($_POST['flip'],array('h','v'))){
        $handle[$i]->image_flip = $_POST['flip'];
    }
    
    // the upload object has some work to do. 
    $newimage = $handle[$i]->process(null);
    if ($handle[$i]->processed) {
        // adding to the Zip archive the result of the previous process
        $zip->addFromString($handle[$i]->file_dst_name_body . '.' . $handle[$i]->file_dst_name_ext, $newimage);
    }
}
// The Zip archive is done
$zip->close();

// Do we have something?
if (filesize($filename) > 1) {
    // Name of the Zip archive to download
    echo $zipfile;
} else {
    // An error occured, nothing to download
    echo 'KO';
}