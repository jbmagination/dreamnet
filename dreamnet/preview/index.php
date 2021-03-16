<?php
// A simple script to render a Glasklart Preview from artwork image
// www.example.net/preview.php?image=http://full_url_to_artwork_png
// originally written by @darkstx
// Rewritten to use Imagick by @dreamnet 2013-03-18

if(!isset($_GET["image"])) { $file1 = false; } else { $file1 = $_GET["image"]; }
if($file1) { $file1 = str_replace(" ","%20", $file1); }

// the background images
$background_150 = 'img/background_150.png';
$background_182 = 'img/background_182.png';
$background_210 = 'img/background_210.png';

$pattern = '/^(https?|ftp)\:\/\/.*png$/i';
if (filter_var($file1, FILTER_VALIDATE_URL) !== false && preg_match($pattern, $file1) && file_get_contents($file1,0,null,0,1)) {

	// looks like we have a valid url and png so let's encode the URL
	$file1 = encodeURL($file1);

	// grab image and save it in tmp (workaround for buggy imagick 6.9.9-33)
    grabRemoteImage($file1);

    $tmpfile = 'tmp/source.png'; // set path to tmpfile
    if(file_exists($tmpfile)) {
        $submitted = new Imagick($tmpfile); // submited image
        unlink($tmpfile);
        $submitted->setImageFormat('png32');
    } else {
        $submitted = new Imagick($file1); // submited image
        $submitted->setImageFormat('png32');
    }

	// getting image geometry
	$geo = $submitted->getImageGeometry();
	$width = $geo['width'];
	$height = $geo['height'];

	$icontype = checkSize($width, $height); // checks the size of the image

	switch($icontype) { // switching trough icontypes

		case 'iPhone2x':

			$img = new Imagick($background_150); // create a new 150x150px image with noised background

			$draw = new ImagickDraw(); // create a new drawing object
			$draw->setFillColor('#ffffffee'); // the text color - the last two bits are for transparency
			$draw->setFont('font/QlassikBold_TB-webfont.ttf'); // the font for the text
			$draw->setFontSize( 10 ); // font-size for the text
			$img->annotateImage($draw, 2, 9, 0, '@2x Preview'); // adding the text to the image

			$img->compositeImage($submitted, $submitted->getImageCompose(), 15, 15); // merging the submitted image above background
			$img->stripImage();
			$img->setImageFormat('png32');

			// Output the Rendered Preview Icon
			header('Content-type: image/png');
			echo $img;
			$draw->destroy();
			$img->destroy();
			$submitted->destroy();

		break;

		case 'iPad2x':

			$img = new Imagick($background_182); // create a new 182x182px image with noised background

			$draw = new ImagickDraw(); // create a new drawing object
			$draw->setFillColor('#ffffffee'); // the text color - the last two bits are for transparency
			$draw->setFont('font/QlassikBold_TB-webfont.ttf'); // the font for the text
			$draw->setFontSize( 10 ); // font-size for the text
			$img->annotateImage($draw, 2, 9, 0, 'iPad@2x Preview'); // adding the text to the image

			$img->compositeImage($submitted, $submitted->getImageCompose(), 15, 15); // merging the submitted image above background
			$img->stripImage();
			$img->setImageFormat('png32');

			// Output the Rendered Preview Icon
			header('Content-type: image/png');
			echo $img;
			$draw->destroy();
			$img->destroy();
			$submitted->destroy();

		break;

		case 'iPhone3x':

			$img = new Imagick($background_210); // create a new 210x210px image with noised background

			$draw = new ImagickDraw(); // create a new drawing object
			$draw->setFillColor('#ffffffee'); // the text color - the last two bits are for transparency
			$draw->setFont('font/QlassikBold_TB-webfont.ttf'); // the font for the text
			$draw->setFontSize( 10 ); // font-size for the text
			$img->annotateImage($draw, 2, 9, 0, '@3x - large Preview'); // adding text to the image

			$img->compositeImage($submitted, $submitted->getImageCompose(), 15, 15); // merging the submitted image above background
			$img->stripImage();
			$img->setImageFormat('png32');

			// Output the Rendered Preview Icon
			header('Content-type: image/png');
			echo $img;
			$draw->destroy();
			$img->destroy();
			$submitted->destroy();

		break;

		case 'iPhone2xold':

			$img = new Imagick($background_150); // create a new 150x150px image with noised background

			$draw = new ImagickDraw(); // create a new drawing object
			$draw->setFillColor('#ffffffee'); // the text color - the last two bits are for transparency
			$draw->setFont('font/QlassikBold_TB-webfont.ttf'); // the font for the text
			$draw->setFontSize( 10 ); // font-size for the text
			$img->annotateImage($draw, 2, 9, 0, 'iOS6 @2x - outdated Preview'); // adding the text to the image

			$img->compositeImage($submitted, $submitted->getImageCompose(), 18, 18); // merging the submitted image above background
			$img->stripImage();
			$img->setImageFormat('png32');

			// Output the Rendered Preview Icon
			header('Content-type: image/png');
			echo $img;
			$draw->destroy();
			$img->destroy();
			$submitted->destroy();

		break;

		case false: // if an wrong sized image is submitted

			echo '<div>Fail: Wrong image size submitted.</div>';
			echo '<div>Only</div>';
			echo '<div>- 120x120px .png\'s (for iOS7 and iOS8 Retina iPhone icons</div>';
			echo '<div>- 152x152px .png\'s (for iOS7 and iOS8 Retina iPad icons</div>';
			echo '<div>- 180x180px .png\'s (for iOS7 and iOS8 Retina iPhone 6+ icons</div>';
			echo '<div>are accepted.</div>';

		break;

	}

} else {

	echo '<div>Fail: Invalid image link submitted. Please check.</div>';

}

function encodeURL($url){
	$startpos = strrpos($url, '/');
	$tmpstr = substr($url, $startpos+1);
	$tmpstr = str_replace("%20"," ", $tmpstr);
	$url = str_replace($tmpstr, rawurlencode($tmpstr),$url);
	return $url;
}

function checkSize($width, $height){
	if ($width == 120 && $height == 120)
		return 'iPhone2x';
	else if ($width == 152 && $height == 152)
		return 'iPad2x';
	else if ($width == 180 && $height == 180)
		return 'iPhone3x';
	else if ($width == 114 && $height == 114)
		return 'iPhone2xold';
	else
		return false;
}

function grabRemoteImage($url) {
    $ch = curl_init($url);
    $fp = fopen('tmp/source.png', 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
}

?>
