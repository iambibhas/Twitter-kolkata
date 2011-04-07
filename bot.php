<?php

include 'config.php';
include 'dynamic_vars.php';
include 'functions.php';

date_default_timezone_set('Asia/Kolkata');

twitterResults($last_got_mention);

$twitter_result_array=Array();

function twitterResults($last_got_mention){
    $url = "http://api.twitter.com/1/iAmBivas/lists/9594987/statuses.json?since_id=" . $last_got_mention;
	// echo $url;
    // sendRequest
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_REFERER, "http://bibhas.in");
    $body = curl_exec($ch);
    curl_close($ch);

    $json = json_decode($body);
    $twitter_result_array=object_to_array($json);
    //echo count($twitter_result_array);
    if(count($twitter_result_array)>0){
        for($i=0;$i<count($twitter_result_array);$i++){
				$datetime = get_kolkata_time($twitter_result_array[$i]['created_at']);
                echo "<li>" . $twitter_result_array[$i]['user']['screen_name'] . ": " . $twitter_result_array[$i]['text'] . " at " . $datetime . "</li>";
                storeTheTweet($twitter_result_array[$i]['user']['screen_name'], $twitter_result_array[$i]['text'], $datetime);
                store_trends($twitter_result_array[$i]['text']);
        }
        saveLastMention("{$twitter_result_array[0]['id_str']}");
    }
    ?>
    <pre><?php //print_r($twitter_result_array); ?></pre>
    <?php
}

function object_to_array($data){
  if(is_array($data) || is_object($data)) //
  {
    $result = array();
    foreach($data as $key => $value)
    {
      $result[$key] = object_to_array($value);
    }
    return $result;
  }
  return $data;
}

function saveLastMention($mention_id){
    $myFile = "dynamic_vars.php";
    $fh = fopen($myFile, 'w+') or die("can't open file");
    $stringData = '<?php $last_got_mention="' . $mention_id . '"; ';
    $stringData .= " ?>";
    fwrite($fh, $stringData);
    fclose($fh);
}

function get_kolkata_time($time_string){
    $timestamp=strtotime($time_string);
    $datetime = date("Y-m-d H:i:s", $timestamp);
    return $datetime;
}

function storeTheTweet($user,$text,$ts){
	$text=mysql_real_escape_string($text);
	if(!empty($text) && !empty($user) && !empty($ts)){
		$con=mysql_connect(DB_HOST,DB_UNAME,DB_PASSWD);
		mysql_select_db("koltweeps",$con);
		mysql_query("INSERT INTO `tweets` (`id`, `from`, `text`, `timestamp`)
				   VALUES (NULL, '{$user}', '{$text}', '{$ts}')");
				   //echo "INSERT INTO `tweets` (`id`, `from`, `text`, `timestamp`) VALUES (NULL, '{$user}', '{$text}', '{$ts}')";
		//$error=mysql_error($con);
		//echo $error;
		mysql_close($con);
	}

}

function store_trends($text){
	$keyword_list=extractCommonWords($text);
	print_r($keyword_list);
	$con=mysql_connect(DB_HOST,DB_UNAME,DB_PASSWD);
	mysql_select_db("koltweeps",$con);
	foreach($keyword_list as $keywrd=>$freq){
		if(mysql_query("UPDATE trends SET frequency = frequency +{$freq} WHERE keyword LIKE '{$keywrd}'")){
			if(mysql_affected_rows()==0)
				mysql_query("INSERT INTO `koltweeps`.`trends` (`id`, `keyword`, `frequency`) VALUES (NULL, '{$keywrd}', '{$freq}')");
		}
	}
	mysql_close($con);
}
?>

