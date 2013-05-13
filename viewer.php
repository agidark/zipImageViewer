<html>
<head>
</head>
<body>


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
		$filename = substr($zip_data[$i][name],-5); // 뒤에서 5개 문자 뽑아옵니다.
		$fn = explode(".",$filename); // .(dot)으로 구분합니다.
		$file_name_ext = $fn[sizeof($fn)-1]; // .(dot)으로 구분하여 뒤에 확장자를 뽑아옵니다.
		
		//image 확장자만 다시 리스트화
		if($file_name_ext == 'jpg' || $file_name_ext == 'png' || $file_name_ext == 'jpeg')
		{
			$image_data[] = array(
				$zip_data[$i][name]
			);
		}
	}
/*	
	for($i = 0 ; $i < sizeof($image_data) ; $i ++){
		echo $image_data[$i][0];
		echo "<br/>";
	}
*/
	return $image_data;
	
}
?>

<?PHP
function drawImageData($image_data){
//		echo "zip://".$_GET[file]."#".$image_data[$_GET[page]][name];

//	$zip = zip_open($zip_file);
//	if($zip)
//	{
//		$left = file_get_contents('zip://'.$_GET[file].'#'.$image_data[$_GET[page]][name]);
//	}
	
	$zip = new ZipArchive();
	$res = $zip->open($_GET[file]);
	if($res == TRUE)
	{
//		$left = file_get_contents('zip://'.$_GET[file].'#'.$image_data[$_GET[page]][name]);
		file_put_contents("left.jpg", file_get_contents('zip://'.$_GET[file].'#'.$image_data[$_GET[page]][0]));
//		$zip->extractTo('left.jpg', 'foo.txt');
		$left_num = $_GET[page] - 2;
		$right_num = $_GET[page] + 2;
		echo "<a href=\"viewer.php?file=".$_GET[file]."&page=".$left_num."\">";
		echo "<img src=\"left.jpg\" height=\"100%\"/>";
		echo "</a>";

		file_put_contents("right.jpg", file_get_contents('zip://'.$_GET[file].'#'.$image_data[$_GET[page]+1][0]));
		echo "<a href=\"viewer.php?file=".$_GET[file]."&page=".$right_num."\">";
		echo "<img src=\"right.jpg\" height=\"100%\"/>";
		echo "</a>";
		
//		echo $left;
	}
}
?>

<?PHP
	$zip_data = readZipData($_GET[file]);
	$image_data = readImageData($zip_data);
	drawImageData($image_data);
	
	
?>

</body>
</html>