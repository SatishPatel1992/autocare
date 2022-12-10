<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UploadModel extends CI_Model {
    
public function fileupload($do="",$folder="") {
    if($do != "") {
       $_REQUEST['do'] = $do;
    }
    if($folder != "") {
       $_REQUEST['folder'] = $folder;
    }
    
    if (!file_exists(DOCUMENT_ROOT . "uploads/" . $_REQUEST['do'])) {
            mkdir(DOCUMENT_ROOT . "uploads/" . $_REQUEST['do'], 0777, true);
    }
    if (isset($_REQUEST['folder']) && $_REQUEST['folder'] != '') {
        if (!file_exists(DOCUMENT_ROOT . "uploads/" . $_REQUEST['do'] . "/" . $_REQUEST['folder'])) {
             mkdir(DOCUMENT_ROOT . "uploads/" . $_REQUEST['do'] . "/" . $_REQUEST['folder'], 0777, true);
        }
        $output_dir = DOCUMENT_ROOT . "uploads/" . $_REQUEST['do'] . "/" . $_REQUEST['folder'] . "/";
        $upload_path_url = UPLOAD_PATH_URL. $_REQUEST['do'] . "/" . $_REQUEST['folder'] . "/";
    } else {
        $output_dir = DOCUMENT_ROOT . "uploads/" . $_REQUEST['do'] . "/";
        $upload_path_url = UPLOAD_PATH_URL. $_REQUEST['do'] . "/";
    }
    if (!file_exists(DOCUMENT_ROOT . "uploads/" . $_REQUEST['do'] . "/thumbs/".$_REQUEST['folder'])) {
        mkdir(DOCUMENT_ROOT . "uploads/" . $_REQUEST['do'] . "/thumbs/".$_REQUEST['folder'], 0777, true);
    }
    $output_dir_thumbs = DOCUMENT_ROOT . "uploads/" . $_REQUEST['do'] . "/thumbs/".$_REQUEST['folder']."/";
    
    if (isset($_FILES["filename"])) {
        $ret = array();
        $error = $_FILES["filename"]["error"];
            
        if (!is_array($_FILES["filename"]["name"])) {
            $temp_name = $_FILES["filename"]["tmp_name"];
            $fileName = time() . '-' . $_FILES["filename"]["name"];
            move_uploaded_file($_FILES["filename"]["tmp_name"], $output_dir . $fileName);
            $ext_array = explode('.', $fileName);
            $extension = $ext_array[sizeof($ext_array) - 1];
            if ($extension == 'jpg' || $extension == 'png' || $extension == 'gif' || $extension == 'jpeg') {
                switch ($extension) {
                        case 'jpg':
                            imagejpeg($new, $file_name, 100);
                            break;
                        case 'jpeg':
                            imagejpeg($new, $file_name, 100);
                            break;
                        case 'png':
                            imagepng($new, $file_name, 9);
                            break;
                        case 'gif':
                            imagegif($new, $file_name, 100);
                            break;
                        default:
                            exit;
                            break;
                    }
                    $_SESSION['file_name'] = $fileName;
                    $this->imageResize($fileName, $output_dir . $fileName, $extension, '100', '100', $output_dir_thumbs);
                }
            }
            return array('link' =>  $upload_path_url. $fileName,'file_name' => $fileName);
            //return $ret;
        }
    }
    public function imageResize($name, $tmp_name, $mimeType, $thumbheight, $thumbwidth, $upload_path_thumb) {
        $image = $name;
        $uploadedfile = $tmp_name;

        if ($mimeType == "jpeg" || $mimeType == "jpg") {
            $src = imagecreatefromjpeg($uploadedfile);
        } else if ($mimeType == "png") {
            $src = imagecreatefrompng($uploadedfile);
        } else if ($mimeType == "gif") {
            $src = imagecreatefromgif($uploadedfile);
        }

        list($width, $height) = getimagesize($uploadedfile);

        $newwidth = $thumbheight;
        $newheight = $thumbwidth;
        $tmp = imagecreatetruecolor($newwidth, $newheight);
        $trans_colour = imagecolorallocatealpha($tmp, 0, 0, 0, 127);
        imagefill($tmp, 0, 0, $trans_colour);
        imagealphablending($tmp, true);
        imagesavealpha($tmp, true);
        imagecopyresized($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        $filename = $upload_path_thumb . $name;

        if ($mimeType == "jpeg" || $mimeType == "jpg") {
            imagejpeg($tmp, $filename, 100);
        } else if ($mimeType == "gif") {
            imagegif($tmp, $filename, 100);
        } else if ($mimeType == "png") {
            imagepng($tmp, $filename, 9);
        }

        //$image = file_get_contents("$filename", true);
        //return $image;
    }
}
?>