<html>
<head>
</head>
<body>
<?PHP
	$add = "./";
	$dir = opendir($add);
	$count = 0;
	$arr;
	while($file = readdir($dir))
	{
		if($file == '.' || $file == '..')
			continue;
		$arr[$count] = $file;
		$count ++;
	}
	
	for($i = 0 ; $i < $count ; $i ++){
		echo "<a href=\"viewer.php?file=".$arr[$i]."&page=0\">";
		echo $arr[$i];
		echo "</a>";
		echo "<br/>";
	}
?>

</body>
</html>