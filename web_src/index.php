<?php	
	// Add to location / block in nginx:
	// try_files $uri $uri/ /index.php?path=$uri;
	// Written by OwN-3m-All (own3mall@gmail.com)
	//
	// @Description:  
	// Pulls rufus files over https but serves them over http for Windows XP

	$contentLength = 0;

	function downloadFileInChunks($path){
		$chunkSize = 1024 * 1024;
		$handle = fopen($path, 'rb');
		while (!feof($handle))
		{
			$buffer = fread($handle, $chunkSize);
			echo $buffer;
			ob_flush();
			flush();
		}
		fclose($handle);
	}
	
	function URLExists($url){
		global $contentLength;
		$headers=get_headers($url, true);
		if(stripos($headers[0],"200 OK") != false){
			$contentLength = $headers['Content-Length'];
			return true;
		}
		return false;
	}

	
	$path = $_GET["path"];
	$path = "https://rufus.ie" . $path;
	if(!empty($path) && $path != "/" && stripos($path, "files") != false){
		if(URLExists($path)){
			header("Content-Type: application/octet-stream");
			header("Content-Length: " . $contentLength );
			header('Content-Disposition: attachment; filename="' . basename($path) . '"');	
			downloadFileInChunks($path);
			exit();
		}else{
			header("HTTP/1.0 404 Not Found");
			die();
		}
	}
	
	header("HTTP/1.0 404 Not Found");
	die();
?>
