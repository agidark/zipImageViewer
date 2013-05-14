<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"> 
<title>Zip image viewer - 
<?PHP
echo $_GET[file];
echo ", page ";
echo $_GET[page];
?>
</title>
<?php
//source
//http://coursesweb.net/php-mysql/read-zip-archive-data-php_cs


// Function to Read ZIP Archive. Returns a two dimensional Array with data of each file in archive
function readZipData($zip_file) {
  // PHP-MySQL Course - http://coursesweb.net/php-mysql/
  $zip_data = array();     // will store arrays with data of each file in archive
  $zip = zip_open($zip_file);

  // if the $zip_file is opened, traverse the archive
  if($zip) {
    while ($zip_entry = zip_read($zip)) {
      // adds in $zip_data an array with data of each file in archive
      $zip_data[] = array(
        'name' =>zip_entry_name($zip_entry),
        'actual_filesize' => zip_entry_filesize($zip_entry),
        'compressed_size' => zip_entry_compressedsize($zip_entry)
      );
    }
    zip_close($zip);

    return $zip_data;
  }
  else  echo "Failed to open $zip_file";
}
?>
<?PHP
//select only image

function readImageData($zip_data){
	$image_data = array();
	
	for($i = 0 ; $i < sizeof($zip_data); $i ++)
	{
		$filename = substr($zip_data[$i][name],-5); // last 5 cahrs
		$fn = explode(".",$filename); // divided by .
		$file_name_ext = $fn[sizeof($fn)-1]; // get extension
		
		//extract image file
		if($file_name_ext == 'jpg' || $file_name_ext == 'png' || $file_name_ext == 'jpeg')
		{
			$image_data[] = array(
				$zip_data[$i][name]
			);
		}
	}

	return $image_data;
	
}
?>

<?PHP
function drawImageData($image_data){

	
	$zip = new ZipArchive();
	$res = $zip->open($_GET[file]);
	if($res == TRUE)
	{

		file_put_contents("left.jpg", file_get_contents('zip://'.$_GET[file].'#'.$image_data[$_GET[page]][0]));

		if($_GET[page] - 2  < 0){
			$left_num = 0;
		}
		else{
			$left_num = $_GET[page] - 2;
		}
		if($_GET[page] + 2 > sizeof($image_data)){
			$right_num = sizeof($image_data) - 1;
		}
		else{
			$right_num = $_GET[page] + 2;
		}
		if($_GET[LtoR] == 1){
			echo "<a href=\"viewer.php?file=".$_GET[file]."&page=".$left_num."&LtoR=".$_GET[LtoR]."\">";
			echo "<img src=\"left.jpg\" height=\"100%\"/>";
			echo "</a>";

			file_put_contents("right.jpg", file_get_contents('zip://'.$_GET[file].'#'.$image_data[$_GET[page]+1][0]));
			echo "<a href=\"viewer.php?file=".$_GET[file]."&page=".$right_num."&LtoR=".$_GET[LtoR]."\">";
			echo "<img src=\"right.jpg\" height=\"100%\"/>";
			echo "</a>";
		}
		else{
			echo "<a href=\"viewer.php?file=".$_GET[file]."&page=".$right_num."&LtoR=".$_GET[LtoR]."\">";
			echo "<img src=\"right.jpg\" height=\"100%\"/>";
			echo "</a>";

			file_put_contents("right.jpg", file_get_contents('zip://'.$_GET[file].'#'.$image_data[$_GET[page]+1][0]));
			echo "<a href=\"viewer.php?file=".$_GET[file]."&page=".$left_num."&LtoR=".$_GET[LtoR]."\">";
			echo "<img src=\"left.jpg\" height=\"100%\"/>";
			echo "</a>";		}
	}
}
?>

</head>
<body>

<div>
<div width="80%" style="float:left;">
<?PHP
	$zip_data = readZipData($_GET[file]);
	$image_data = readImageData($zip_data);
	drawImageData($image_data);
	
?>
</div>
<div width="20%" style="folat:right;">

<?PHP
//page number, goto button.
?>
<form method="get" action="viewer.php">
<input name="page" type="number"/ min="1" value="<?PHP
$page = $_GET[page] + 1;
echo $page;
?>
" max="<?PHP
	echo sizeof($image_data);
?>
" method="GET"/>
<input type="text" name="file" method="GET" hidden="true" value="<?PHP
	echo $_GET[file];
?>
"/>
<input type="submit" value="go"/>
<br/>

<?PHP
//LtoR, RtoL
?>

<input type="radio" name="LtoR" <?PHP
	if($_GET[LtoR] == 1)
		echo "checked=\"true\"";
?>
 value="1" method="get" onClick="location.href='viewer.php?file=<?PHP
 echo $_GET[file];
 ?>
&page=<?PHP
 echo $_GET[page];
 ?>
&LtoR=1'">LtoR
<input type="radio" name="LtoR" <?PHP
	if($_GET[LtoR] == 0)
		echo "checked=\"true\"";
?>
 value="0" method="get" onClick="location.href='viewer.php?file=<?PHP
 echo $_GET[file];
 ?>
&page=<?PHP
 echo $_GET[page];
 ?>
&LtoR=0'">
 RtoL
</form>

<?PHP
//go back button
?>
<form action="index.php">
<input type="submit" value="go back"/>
</form>


</div>
</div>

</body>
</html>