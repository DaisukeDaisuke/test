<?php
if (empty($_FILES['imagefile']['tmp_name']) && (empty($argv[1]))) {
?>
<html>
<head>
<title> ドット絵っぽく見せる変換ツール </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body bgcolor="#f0ffff">
<form enctype="multipart/form-data" action="cs.php?ext=.png" method="POST">
     <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
      画像ファイル(GIF/PNG/JPEGをアップロード: <input name="imagefile" type="file" /><br />
     <!--倍率: <input name="scale" value="2" type="text"/> <br />
     マージン: <input name="margin" value="1" type="text"/> <br />-->
     <input type="submit" value="ファイルを送信" />
<p> 小さい画像を入れてね (はーと) </p><br />
<a href = "http://192.168.0.14:8080/cs.php">戻る</a>
</form>
<?php
                
        exit (0);
}
$time_setup = microtime(true);
error_reporting(E_ALL);
ini_set('memory_limit', '1000M');
set_time_limit(0);
if (isset($_FILES['imagefile']['tmp_name'])) {
    $imagefile = $_FILES['imagefile']['tmp_name'];
} else {
    $imagefile = $argv[1];
}

function getimagecoloralpha($im, $red, $green, $blue, $alpha) {
	$color = imagecolorexactalpha($im, $red, $green, $blue, $alpha);
	if ($color < 0) {
		$color = imagecolorallocatealpha($im, $red, $green, $blue, $alpha);
	}
	return $color;
}
function color($color1,$color2){
        return sqrt(($color2["red"]-$color1[0]) * ($color2["red"]-$color1[0]) + ($color2["green"]-$color1[1]) * ($color2["green"]-$color1[1]) + ($color2["blue"]-$color1[2]) * ($color2["blue"]-$color1[2])) / 1.73205080757; //sqrt(3);
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
$woolColors = [
    [221,221,221,35,0],  # 0: White
    [62,125,219,35,1], # 1: Orange
    [188,80,179,35,2], # 2: Magenta
    [201,138,107,35,3], # 3: Light blue
    [39,166,177,35,4], # 4: Yellow
    [56,174,65,35,5], # 5: Lime
    [153,132,208,35,6], # 6: Pink
    [64,64,64,35,7], # 7: Gray
    [161,161,154,35,8], # 8: Light gray
    [137,110,46,35,9], # 9: Cyan
    [181,61,126,35,10], # 10: Purple
    [141,56,46,35,11], # 11: Blue
    [31,50,79,35,12], # 12: Brown
    [27,70,53,35,13], # 13: Green
    [48,52,150,35,14], # 14: Red
    [22,22,25,35,15], # 15:Black
    //拡張色
    [164,168,184,82,0], #粘土ブロック
    //[229,229,51,35,4], # 4: Yellow 競合
];
$BaseMapColors = [
//36色
			[0, 0, 0, 0],
			[127, 178, 56],
			[247, 233, 163],
			[167, 167, 167],
			[255, 0, 0],
			[160, 160, 255],
			[167, 167, 167],
			[0, 124, 0],
			[255, 255, 255],
			[164, 168, 184],
			[183, 106, 47],
			[112, 112, 112],
			[64, 64, 255],
			[104, 83, 50],
			//new 1.7 colors (13w42a/13w42b)
			[255, 252, 245],
			[216, 127, 51],
			[178, 76, 216],
			[102, 153, 216],
			[229, 229, 51],
			[127, 204, 25],
			[242, 127, 165],
			[76, 76, 76],
			[153, 153, 153],
			[76, 127, 153],
			[127, 63, 178],
			[51, 76, 178],
			[102, 76, 51],
			[102, 127, 51],
			[153, 51, 51],
			[25, 25, 25],
			[250, 238, 77],
			[92, 219, 213],
			[74, 128, 255],
			[0, 217, 58],
			[21, 20, 31],
			[112, 2, 0],
			//new 1.8 colors
			[126, 84, 48],
];

$jsoncolor = json_decode(base64_decode('W1sxOTAsNjksMTgwXSxbMTEyLDE4NSwyNl0sWzExNCw3Miw0MV0sWzIzOCwxNDEsMTcyXSxbMTQyLDE0MiwxMzVdLFsyNDksMTk4LDQwXSxbMjM0LDIzNiwyMzddLFs4NSwxMTAsMjhdLFsxMjIsNDIsMTczXSxbNjgsNCw3XSxbMjEsMTM4LDE0NV0sWzE2OSw4OCwzM10sWzIzNiwyMzMsMjI2XSxbMTA0LDc4LDQ3XSxbMTk2LDE3OSwxMjNdLFsyMTksMjExLDE2MF0sWzM5LDY3LDEzOF0sWzE1NCwxMTAsNzddLFsxMDQsMTE4LDUzXSxbMTQzLDYxLDQ3XSxbMjE5LDIxOSwyMTldLFsxNTEsOTMsNjddLFs2MSw0MCwxOF0sWzE2MSwzOSwzNV0sWzc3LDUxLDM2XSxbMjIwLDIxMiwxNjJdLFsxNjgsODYsMzFdLFs3Niw4Myw0Ml0sWzc0LDE4MSwyMTNdLFsxMTgsNzAsODZdLFszNywyMywxNl0sWzU4LDQyLDM2XSxbODcsOTEsOTFdLFsxMzIsNTYsMTc4XSxbNTUsNTgsNjJdLFsyMSwxMTksMTM2XSxbMTY5LDQ4LDE1OV0sWzE5LDE5LDE5XSxbMTY5LDkyLDUxXSxbMjQ5LDIzNiw3OV0sWzc0LDYwLDkxXSxbNDUsNDcsMTQzXSxbOTYsNjAsMzJdLFsxMzUsMTA3LDk4XSxbMTM0LDk2LDY3XSxbMTYyLDc4LDc5XSxbNzMsOTEsMzZdLFs4LDEwLDE1XSxbMTI3LDEyNCwxMjNdLFsyMTAsMTc4LDE2MV0sWzE1NywxMjgsNzldLFsxNTksMTY0LDE3N10sWzExMywxMDksMTM4XSxbMjMzLDE5OSw1NV0sWzE2Miw4NCwzOF0sWzcwLDczLDE2N10sWzIxNCwxMDEsMTQzXSxbMTI4LDE2Nyw4NV0sWzk3LDE1Myw5N10sWzk1LDE2Miw3MF0sWzE5NiwyMjEsMTc2XSxbMTA2LDExMiw1N10sWzkxLDExNSw1OV0sWzIyNywxMzIsMzJdLFsxOTMsODQsMTg1XSxbMzYsMTM3LDE5OV0sWzEwMCwzMiwxNTZdLFsxMjYsODUsNTRdLFs3Nyw4MSw4NV0sWzIyNiwyMjcsMjI4XSxbMjI5LDE1MywxODFdLFsxNTUsMTU1LDE0OF0sWzY1LDY1LDY1XSxbOTQsMTY5LDI1XSxbOTcsMTE5LDQ1XSxbMTY4LDU0LDUxXSxbMjA3LDIxMywyMTRdLFsxMjUsMTg5LDQyXSxbMjUsMjcsMzJdLFsxNTAsODgsMTA5XSxbMzcsMTQ4LDE1N10sWzk4LDIxOSwyMTRdLFsyMjQsOTcsMV0sWzE0MiwzMywzM10sWzEyNSwxMjUsMTE1XSxbMjQxLDE3NSwyMV0sWzEyNSwxMjUsMTI1XSxbMTgzLDE4MywxODZdLFsyMSwyMSwyNl0sWzE1OSwxMTUsOThdLFsyNDEsMTE4LDIwXSxbNTgsMTc1LDIxN10sWzYzLDY4LDcyXSxbNTMsNTcsMTU3XSxbMTMzLDEzMywxMzVdXQ=='), true);



$correctioncolor = json_decode(base64_decode('W1sxMTIsMTg1LDI2LDM1LDVdLFsxMTQsNzIsNDEsMzUsMTJdLFsyMzgsMTQxLDE3MiwzNSw2XSxbMTQyLDE0MiwxMzUsMzUsOF0sWzI0OSwxOTgsNDAsMzUsNF0sWzIzNCwyMzYsMjM3LDM1LDBdLFs4NSwxMTAsMjgsMzUsMTNdLFsxMjIsNDIsMTczLDM1LDEwXSxbMjEsMTM4LDE0NSwzNSw5XSxbMTY5LDg4LDMzLDEyLDFdLFsyMzYsMjMzLDIyNiwxNTUsMF0sWzEwNCw3OCw0Nyw1LDFdLFsxOTYsMTc5LDEyMyw1LDJdLFsyMTksMjExLDE2MCwxMiwwXSxbOTgsMjE5LDIxNCw1NywwXSxbMTU0LDExMCw3Nyw1LDNdLFsyMTksMjE5LDIxOSw0MiwwXSxbMTkwLDY5LDE4MCwzNSwyXSxbNjEsNDAsMTgsNSw1XSxbMTYxLDM5LDM1LDM1LDE0XSxbNzQsMTgxLDIxMywyMzcsM10sWzEzMiw1NiwxNzgsMjM3LDEwXSxbMTA0LDExOCw1MywxNTksNV0sWzE0Myw2MSw0NywxNTksMTRdLFs3Nyw1MSwzNiwxNTksMTJdLFs3Niw4Myw0MiwxNTksMTNdLFsxMTgsNzAsODYsMTU5LDEwXSxbMzcsMjMsMTYsMTU5LDE1XSxbNTgsNDIsMzYsMTU5LDddLFs4Nyw5MSw5MSwxNTksOV0sWzk0LDE2OSwyNSwyMzYsNV0sWzIxLDExOSwxMzYsMjM2LDldLFsxNjksNDgsMTU5LDIzNiwyXSxbMTksMTksMTksMTczLDBdLFsxNjksOTIsNTEsNSw0XSxbMjQ5LDIzNiw3OSw0MSwwXSxbNzQsNjAsOTEsMTU5LDExXSxbNDUsNDcsMTQzLDIzNiwxMV0sWzk2LDYwLDMyLDIzNiwxMl0sWzEzNSwxMDcsOTgsMTU5LDhdLFsxNjIsNzgsNzksMTU5LDZdLFs3Myw5MSwzNiwyMzYsMTNdLFs4LDEwLDE1LDIzNiwxNV0sWzIxMCwxNzgsMTYxLDE1OSwwXSxbMTU3LDEyOCw3OSw1LDBdLFszOSw2NywxMzgsMjIsMF0sWzE1OSwxNjQsMTc3LDgyLDBdLFsxMTMsMTA5LDEzOCwxNTksM10sWzIzMywxOTksNTUsMjM3LDRdLFsxNjIsODQsMzgsMTU5LDFdLFs3MCw3MywxNjcsMjM3LDExXSxbMjE0LDEwMSwxNDMsMjM2LDZdLFsxOTMsODQsMTg1LDIzNywyXSxbMjI3LDEzMiwzMiwyMzcsMV0sWzM2LDEzNywxOTksMjM2LDNdLFsxMDAsMzIsMTU2LDIzNiwxMF0sWzEyNiw4NSw1NCwyMzcsMTJdLFs3Nyw4MSw4NSwyMzcsN10sWzIyNiwyMjcsMjI4LDIzNywwXSxbMjI5LDE1MywxODEsMjM3LDZdLFsxNTUsMTU1LDE0OCwyMzcsOF0sWzI0MSwxNzUsMjEsMjM2LDRdLFs1NSw1OCw2MiwyMzYsN10sWzk3LDExOSw0NSwyMzcsMTNdLFsxNjgsNTQsNTEsMjM3LDE0XSxbMjA3LDIxMywyMTQsMjM2LDBdLFsxMjUsMTg5LDQyLDIzNyw1XSxbMjUsMjcsMzIsMjM3LDE1XSxbMTUwLDg4LDEwOSwxNTksMl0sWzM3LDE0OCwxNTcsMjM3LDldLFsyMjQsOTcsMSwyMzYsMV0sWzE0MiwzMywzMywyMzYsMTRdLFsxMjUsMTI1LDExNSwyMzYsOF0sWzIxLDIxLDI2LDM1LDE1XSxbMTI1LDEyNSwxMjUsMSwwXSxbMTgzLDE4MywxODYsMSw0XSxbMTU5LDExNSw5OCwxLDJdLFsyNDEsMTE4LDIwLDM1LDFdLFs1OCwxNzUsMjE3LDM1LDNdLFs2Myw2OCw3MiwzNSw3XSxbNTMsNTcsMTU3LDM1LDExXSxbMTMzLDEzMywxMzUsMSw2XV0='), true);

$plus_color = array (
  0 => 
  array (
    0 => 72,
    1 => 154,
    2 => 94,
  ),
  1 => 
  array (
    0 => 163,
    1 => 51,
    2 => 64,
  ),
  2 => 
  array (
    0 => 53,
    1 => 82,
    2 => 149,
  ),
  3 => 
  array (
    0 => 156,
    1 => 127,
    2 => 128,
  ),
  4 => 
  array (
    0 => 170,
    1 => 110,
    2 => 59,
  ),
  5 => 
  array (
    0 => 163,
    1 => 161,
    2 => 77,
  ),
  6 => 
  array (
    0 => 127,
    1 => 75,
    2 => 121,
  ),
  7 => 
  array (
    0 => 76,
    1 => 130,
    2 => 168,
  ),
  8 => 
  array (
    0 => 54,
    1 => 54,
    2 => 61,
  ),
  9 => 
  array (
    0 => 149,
    1 => 103,
    2 => 137,
  ),
  10 => 
  array (
    0 => 56,
    1 => 101,
    2 => 73,
  ),
  11 => 
  array (
    0 => 127,
    1 => 78,
    2 => 73,
  ),
  12 => 
  array (
    0 => 221,
    1 => 221,
    2 => 221,
  ),
);

$use_mapcolor = false;
//trueにすると多くの色を使います。

$use_jsoncolor = false;
//trueにするともっと多くの色を使います
$ues_correctioncolor = true;
//↑の修正版を利用します

$use_plus_color = false;

if($use_mapcolor) $woolColors = $BaseMapColors;
if($use_jsoncolor) $woolColors = $jsoncolor;
if($ues_correctioncolor) $woolColors = $correctioncolor;
if($use_plus_color) $woolColors = $plus_color;
unset($BaseMapColors);
unset($jsoncolor);
unset($correctioncolor);
//http://minecraft-ja.gamepedia.com/地図アイテムフォーマット
$time_start = microtime(true);
$setup = microtime(true) - $time_setup;

$width  = imagesx($im);
$height = imagesy($im);
$im2 = imagecreatetruecolor($width,$height);
/*
$out = ImageCreateTrueColor($width/4, $height/4);
ImageCopyResampled($out, $im,0,0,0,0, $width/4, $height/4, $width, $height);
$im = $out;
$width  = imagesx($im);
$height = imagesy($im);

$im2 = imagecreatetruecolor($width,$height);
*/
$time_get = microtime(true);

$cash = [];
$cash1 = [];
foreach($woolColors as $colors)
	$cash1[] = getimagecoloralpha($im2,$colors[0],$colors[1],$colors[2],0);
echo "woolcolor array ".(microtime(true) - $time_get)."<br />";

$time_loop = microtime(true);
for ($y = 0 ; $y < $height ; ++$y){
    for ($x = 0 ; $x < $width ; ++$x){
	$data = imagecolorat($im,$x,$y);
	$r = ($data >> 16) & 0xFF;
	$g = ($data >> 8) & 0xFF;
	$b = $data & 0xFF;
	$selectedColor = 0;
	$minDist = -1;
		if(isset($cash[$data])){
			$selectedColor = $cash[$data];
		}else{
			foreach($woolColors as $i => $woolcolor){
				$dist = ($r-$woolcolor[0]) * ($r-$woolcolor[0]) + ($g-$woolcolor[1]) * ($g-$woolcolor[1]) + ($b-$woolcolor[2]) * ($b-$woolcolor[2]);
				if($minDist === -1||$minDist > $dist){
					$minDist = $dist;
					$selectedColor = $i;
				}
			}
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
