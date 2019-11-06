<?php
ini_set('memory_limit', '1G');

$originalDir = '/tmp/original';

$fi = new FilesystemIterator($originalDir, FilesystemIterator::SKIP_DOTS);
$numberOfPicturesOriginal = iterator_count($fi);

$numberOfPicturesProcessed = 12;
$rotationAngle = 360 / $numberOfPicturesProcessed;
$arcAngle = (180 - $rotationAngle) / 2;

[,$imageHeight,,]=getimagesize("$originalDir/thumb0001.jpg");
$wheelDimensions=$imageHeight*2;

$finalWheel = imagecreatetruecolor($wheelDimensions, $wheelDimensions);
imagesavealpha($finalWheel, true);
imagefill($finalWheel, 0, 0, imagecolorallocate($finalWheel, 255, 255, 255));

$pictureNumber = 1;
for($i=1; $i<$numberOfPicturesOriginal; $i+=$numberOfPicturesOriginal/$numberOfPicturesProcessed) {
    echo "Processing image $pictureNumber/$numberOfPicturesProcessed\n";
    $fileName='thumb'.str_pad(round($i), 4, '0', STR_PAD_LEFT);
    $image = imagecreatefromjpeg("$originalDir/$fileName.jpg");
    imagesavealpha($image, true);
    $width = imagesx($image);
    $height = imagesy($image);

    $processed = imagecreatetruecolor($width, $height);
    imagecopy($processed, $image, 0, 0, 0, 0, $width, $height);
    imagedestroy($image);

    $pink = imagecolorallocate($processed, 255, 192, 203);
    $arcEndY = $height * sin(deg2rad($arcAngle));
    imagefilledpolygon($processed, [0, 0, $width, 0, $width, $arcEndY, $width/2, 0, 0, $arcEndY], 5, $pink);

    imageellipse($processed, $width/2, 0, $height*2, $height*2, $pink);
    imagefilltoborder($processed, $width/4, $height -1, $pink, $pink);
    imagefilltoborder($processed, 3*$width/4, $height -1, $pink, $pink);
    imagecolortransparent($processed, $pink);

    $wheel = imagecreatetruecolor($wheelDimensions, $wheelDimensions);
    $white = imagecolorallocate($wheel, 255, 255, 255);
    $red = imagecolorallocate($wheel, 255, 0, 0);
    imagefill($wheel, 0, 0, $white);
    imageellipse($wheel, $wheelDimensions / 2, $wheelDimensions / 2, $wheelDimensions, $wheelDimensions, $red);
    imagesavealpha($wheel, true);
    imagesetinterpolation($wheel, IMG_BELL);
    imagecopymerge($wheel, $processed, $wheelDimensions / 2 - imagesx($processed) / 2, $wheelDimensions / 2, 0, 0, imagesx($processed), imagesy($processed), 100);
    imagedestroy($processed);

    $wheel = imagerotate($wheel, $rotationAngle * $pictureNumber, $white);
    $wheel = imagecropauto($wheel, IMG_CROP_WHITE);
    imagecolortransparent($wheel, $white);

    imagecopymerge($finalWheel, $wheel, 0, 0, 0, 0, $wheelDimensions, $wheelDimensions, 100);
    imagejpeg($finalWheel, 'wheel.jpg');
    imagedestroy($wheel);

    $pictureNumber++;
}
