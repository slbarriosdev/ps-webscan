<?php
	 
	 require_once 'config.php';
	 require_once 'send-email-php/send-email.php';
	 
	
	 function nowww($text) {
		$word = array(
		   "http://" => "",
		   "https://" => "",
		   "www." => "",
		);

		foreach ($word as $bad => $good) {
		   $text = str_replace($bad, $good, $text);
		}
				
			$oldurl = explode("/", $text);
			$newurl = $oldurl[0];
			$text = "$newurl";
		
			$text = strip_tags(addslashes($text));
			
		 return $text;
		}

        function whritelog($text){
			
			chdir(FILEDIR);
	        $filename = FILENAME;
			$logFile = fopen($filename, 'a') or die("Error creando archivo");
			fwrite($logFile, "\n".date("m/d/Y H:i:s").$text) or die("Error escribiendo en el archivo");
			
			if(filesize($filename) >= 500000)
			   unlink($filename);
         
			fclose($logFile);
		}


		function findline(){

			chdir(FILEDIR);
	        $filename = FILENAME;
			$lastmailingdate = "05/31/1989 12:00:00";
			$aux = "";

			$logFile = fopen($filename, 'r');

			$phrase="LastEmailSent:";   

			while ($line = fgets($logFile)) {
				if (strstr($line,$phrase)) 
				     $aux = $line;	
			}

			if($aux != ""){
			  $array = explode($phrase, $aux);
			  $lastmailingdate = trim($array[0]);
			}

			fclose($logFile);

			return $lastmailingdate;
		}
			   
	   		
		$url = URL;	   
		$site = nowww("$url"); 
		$check = @fsockopen($site, 80); 
	   
		if ($check) { 
			whritelog(" the page $site is online ");				
		} 
		else { 				    
			
			$last_emailsent = new DateTime((string)findline());
			$datetime = new DateTime();
			$interval = $last_emailsent->diff($datetime);
			
			
			if($interval->format('%a') != "0" || $interval->format('%H') != "00" ){
				sendemail($site);
				whritelog(" LastEmailSent: $site is DOWN");
				
			}
			
		} 

		
   
?>

