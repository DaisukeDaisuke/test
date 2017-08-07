<?php
if(empty($_FILES['imagefile']['tmp_name']) && (empty($argv[1]))){
?>
<html>
<head>
<title> ドット絵っぽく見せる変換ツール </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body bgcolor="#f0ffff">
<form enctype="multipart/form-data" action="cs.php?ext=.png" method="POST">
     <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
      画像ファイル(GIF/PNG/JPEGをアップロード: <input name="imagefile" type="file" /><br />
     <input type="submit" value="ファイルを送信" />
<p> コード削減版 </p><br />
</form>
<?php
                
        exit (0);
}
$time_setup = microtime(true);
error_reporting(E_ALL);
ini_set('memory_limit', '1000M');
set_time_limit(0);
if(isset($_FILES['imagefile']['tmp_name'])){
	$imagefile = $_FILES['imagefile']['tmp_name'];
}else{
	$imagefile = $argv[1];
}

function getimagecoloralpha($im, $red, $green, $blue, $alpha){
	$color = imagecolorexactalpha($im, $red, $green, $blue, $alpha);
	if($color < 0){
		$color = imagecolorallocatealpha($im, $red, $green, $blue, $alpha);
	}
	return $color;
}

$imageinfo = getimagesize($imagefile);

switch ($imageinfo[2]) {
    case IMAGETYPE_GIF:
	$im = imagecreatefromgif($imagefile);
	break;
    case IMAGETYPE_PNG:
	$im = imagecreatefrompng($imagefile);
	break;
    case IMAGETYPE_JPEG:
	$im = imagecreatefromjpeg($imagefile);
	break;
    default:
	echo "we want gif or png file\n";
	usage();
	exit(1);
}
$woolColors = json_decode(base64_decode('W1sxMTIsMTg1LDI2LDM1LDVdLFsxMTQsNzIsNDEsMzUsMTJdLFsyMzgsMTQxLDE3MiwzNSw2XSxbMTQyLDE0MiwxMzUsMzUsOF0sWzI0OSwxOTgsNDAsMzUsNF0sWzIzNCwyMzYsMjM3LDM1LDBdLFs4NSwxMTAsMjgsMzUsMTNdLFsxMjIsNDIsMTczLDM1LDEwXSxbMjEsMTM4LDE0NSwzNSw5XSxbMTY5LDg4LDMzLDEyLDFdLFsyMzYsMjMzLDIyNiwxNTUsMF0sWzEwNCw3OCw0Nyw1LDFdLFsxOTYsMTc5LDEyMyw1LDJdLFsyMTksMjExLDE2MCwxMiwwXSxbOTgsMjE5LDIxNCw1NywwXSxbMTU0LDExMCw3Nyw1LDNdLFsyMTksMjE5LDIxOSw0MiwwXSxbMTkwLDY5LDE4MCwzNSwyXSxbNjEsNDAsMTgsNSw1XSxbMTYxLDM5LDM1LDM1LDE0XSxbNzQsMTgxLDIxMywyMzcsM10sWzEzMiw1NiwxNzgsMjM3LDEwXSxbMTA0LDExOCw1MywxNTksNV0sWzE0Myw2MSw0NywxNTksMTRdLFs3Nyw1MSwzNiwxNTksMTJdLFs3Niw4Myw0MiwxNTksMTNdLFsxMTgsNzAsODYsMTU5LDEwXSxbMzcsMjMsMTYsMTU5LDE1XSxbNTgsNDIsMzYsMTU5LDddLFs4Nyw5MSw5MSwxNTksOV0sWzk0LDE2OSwyNSwyMzYsNV0sWzIxLDExOSwxMzYsMjM2LDldLFsxNjksNDgsMTU5LDIzNiwyXSxbMTksMTksMTksMTczLDBdLFsxNjksOTIsNTEsNSw0XSxbMjQ5LDIzNiw3OSw0MSwwXSxbNzQsNjAsOTEsMTU5LDExXSxbNDUsNDcsMTQzLDIzNiwxMV0sWzk2LDYwLDMyLDIzNiwxMl0sWzEzNSwxMDcsOTgsMTU5LDhdLFsxNjIsNzgsNzksMTU5LDZdLFs3Myw5MSwzNiwyMzYsMTNdLFs4LDEwLDE1LDIzNiwxNV0sWzIxMCwxNzgsMTYxLDE1OSwwXSxbMTU3LDEyOCw3OSw1LDBdLFszOSw2NywxMzgsMjIsMF0sWzE1OSwxNjQsMTc3LDgyLDBdLFsxMTMsMTA5LDEzOCwxNTksM10sWzIzMywxOTksNTUsMjM3LDRdLFsxNjIsODQsMzgsMTU5LDFdLFs3MCw3MywxNjcsMjM3LDExXSxbMjE0LDEwMSwxNDMsMjM2LDZdLFsxOTMsODQsMTg1LDIzNywyXSxbMjI3LDEzMiwzMiwyMzcsMV0sWzM2LDEzNywxOTksMjM2LDNdLFsxMDAsMzIsMTU2LDIzNiwxMF0sWzEyNiw4NSw1NCwyMzcsMTJdLFs3Nyw4MSw4NSwyMzcsN10sWzIyNiwyMjcsMjI4LDIzNywwXSxbMjI5LDE1MywxODEsMjM3LDZdLFsxNTUsMTU1LDE0OCwyMzcsOF0sWzI0MSwxNzUsMjEsMjM2LDRdLFs1NSw1OCw2MiwyMzYsN10sWzk3LDExOSw0NSwyMzcsMTNdLFsxNjgsNTQsNTEsMjM3LDE0XSxbMjA3LDIxMywyMTQsMjM2LDBdLFsxMjUsMTg5LDQyLDIzNyw1XSxbMjUsMjcsMzIsMjM3LDE1XSxbMTUwLDg4LDEwOSwxNTksMl0sWzM3LDE0OCwxNTcsMjM3LDldLFsyMjQsOTcsMSwyMzYsMV0sWzE0MiwzMywzMywyMzYsMTRdLFsxMjUsMTI1LDExNSwyMzYsOF0sWzIxLDIxLDI2LDM1LDE1XSxbMTI1LDEyNSwxMjUsMSwwXSxbMTgzLDE4MywxODYsMSw0XSxbMTU5LDExNSw5OCwxLDJdLFsyNDEsMTE4LDIwLDM1LDFdLFs1OCwxNzUsMjE3LDM1LDNdLFs2Myw2OCw3MiwzNSw3XSxbNTMsNTcsMTU3LDM1LDExXSxbMTMzLDEzMywxMzUsMSw2XV0='), true);

function findNearBlockIndex($rgb,&$woolColors){
	$selectedColor = 0;
	$minDist = -1;
	$r = (($rgb >> 16) & 0xFF);
	$g = ($rgb >> 8) & 0xFF;
	$b = $rgb & 0xFF;
	foreach($woolColors as $i => $woolcolor){
		$dist = ($r-$woolcolor[0]) * ($r-$woolcolor[0]) + ($g-$woolcolor[1]) * ($g-$woolcolor[1]) + ($b-$woolcolor[2]) * ($b-$woolcolor[2]);
		if($minDist === -1||$minDist > $dist){
			$minDist = $dist;
			$selectedColor = $i;
		}
	}
	return $selectedColor;
}
echo "load".(microtime(true) - $time_setup)."<br />";
$width  = imagesx($im);
$height = imagesy($im);
$im2 = imagecreatetruecolor($width,$height);

$time_get = microtime(true);

$cash = [];
$cash1 = [];
foreach($woolColors as $colors)
	$cash1[] = getimagecoloralpha($im2,$colors[0],$colors[1],$colors[2],0);
echo "woolcolor array ".(microtime(true) - $time_get)."<br />";

$time_loop = microtime(true);
for ($y = 0 ; $y < $height ; ++$y){
    for ($x = 0 ; $x < $width ; ++$x){
		$selectedColor = 0;
		$data = imagecolorat($im,$x,$y);
		if(isset($cash[$data])){
			$selectedColor = $cash[$data];
		}else{
			$selectedColor = findNearBlockIndex($data,$woolColors);
			$cash[$data] = $selectedColor;
		}
		imagefilledrectangle($im2, $x, $y, $x, $y, $cash1[$selectedColor]);
	}
}
echo "loop".(microtime(true) - $time_loop)."<br />";
ob_start();
imagepng($im2);
$content = base64_encode(ob_get_contents());
ob_end_clean();

imagedestroy($im2);
imagedestroy($im);
$counts = [];
echo "使用色数::".(count($woolColors)-1)."<br />";
$time = microtime(true) - $time_setup;
echo "<br />${time}秒<br />";
?>
<br /><br /><br /><br /><img src="data:image/png;base64,<?php echo $content;?>" alt="output" />
