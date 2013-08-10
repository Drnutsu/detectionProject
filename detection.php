<?php
 // cURL funtion
  $url = "http://nutsu.kmi.tl/miniAdmin/";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLPOT_POST, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $content = md5(curl_exec($ch));
  curl_close($ch);
  
  $link = @mysql_connect("localhost", ".....", ".....");
  if (!$link) {
    die('Could not connect: ' . mysql_error());
  }
  echo '<br />Connected successfully';
  $today = date("d-m-Y H:i:s");
  $database = mysql_select_db("nutsu");
  
  
  $lastkey = mysql_query("SELECT MAX(hashKey) FROM detection");
  while($k = mysql_fetch_array($lastkey)){
    $kk = $k['MAX(hashKey)'];
  }
    echo "<br />".$kk."<br />";
  $lastInfo = mysql_query("SELECT hash FROM detection WHERE hashKey = ".$kk);
  while($last = mysql_fetch_array($lastInfo)){
  $lastk = $last['hash'];
  }
  echo "content : ".$content."<br />";
  echo "lastk : ".$lastk."<br />";
  if(strcasecmp($content, $lastk) == 0){
    echo "<br />not changed";  
  }else{
  
    //query on database
	
   $query = mysql_query("INSERT INTO detection (time, hash, error) VALUES ('".$today."','".$content."','none')");
   if($query){
		echo "success"."<br />";
	}else{
		echo "<br />failed"."[ ".mysql_error($link)." ]<br />";
	}
   mysql_close($link);
   //Facebook Post
   //-- Facebook API --//
   require_once 'facebook/facebook.php';

  //-- App Information --//
  $app_id       = '........';
  $app_secret   = '........';
  $access_token = '........';

  // Create Facebook Instance
  $facebook = new Facebook(array(
    'appId' => $app_id,
    'secret' => $app_secret,
    'cookie' => true
    ));

  //-- Customizable options to send Facebook --//
  $req =  array(
    'access_token' => $access_token,
    'message' => 'detected new change!!!');

  //-- Send post to Facebook --//
  $res = $facebook->api('.....your group id..../feed', 'POST', $req); 
  }
  
?>
