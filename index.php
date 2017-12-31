<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <form method="post" action="index.php" enctype="multipart/form-data">
    <input type="file" name="file_to" required>
    <input type="submit" name="submit" value="Submit" />
    </form>
  </body>
</html>
<?php
if(isset($_POST['submit'])){

    $file;
    //= "resized/".$_FILES['file_to']['name'];
    $file_dir=$_FILES['file_to']['tmp_name'];
    // $move=move_uploaded_file($_FILES['file']['tmp_name'], $file);
    // if (!$move) {
    //   echo "Unable to move";
    // }
    $widths=array(270,50);
    $heights=array(160,50);
    $aar_count=count($widths)-1;
    for ($i=0; $i <=$aar_count; $i++) {
        $file_name="resized".$i;
        $file = $file_name."/".$_FILES['file_to']['name'];
        resize_crop_image($widths[$i], $heights[$i], $file_dir,$file);
    }

}

//resize and crop image by center
function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 80){
    $imgsize = getimagesize($source_file);
    $width = $imgsize[0];
    $height = $imgsize[1];
    $mime = $imgsize['mime'];

    switch($mime){
        case 'image/gif':
            $image_create = "imagecreatefromgif";
            $image = "imagegif";
            break;

        case 'image/png':
            $image_create = "imagecreatefrompng";
            $image = "imagepng";
            $quality = 7;
            break;

        case 'image/jpeg':
            $image_create = "imagecreatefromjpeg";
            $image = "imagejpeg";
            $quality = 80;
            break;

        default:
            return false;
            break;
    }

    $dst_img = imagecreatetruecolor($max_width, $max_height);
    $src_img = $image_create($source_file);

    $width_new = $height * $max_width / $max_height;
    $height_new = $width * $max_height / $max_width;
    //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
    if($width_new > $width){
        //cut point by height
        $h_point = (($height - $height_new) / 2);
        //copy image
        imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
    }else{
        //cut point by width
        $w_point = (($width - $width_new) / 2);
        imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
    }

    $image($dst_img, $dst_dir, $quality);

    if($dst_img)imagedestroy($dst_img);
    if($src_img)imagedestroy($src_img);
    echo "We are done!";
}
//usage example
//resize_crop_image(900, 500, "test.jpg", "test.jpg");


 ?>
