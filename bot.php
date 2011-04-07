<?php

include 'config.php';
include 'dynamic_vars.php';
include 'functions.php';

date_default_timezone_set('Asia/Kolkata');

twitterResults($last_got_mention);

$twitter_result_array=Array();

function twitterResults($last_got_mention){
    $url = "http://api.twitter.com/1/iAmBivas/lists/9594987/statuses.json";
	//echo $url;
    // sendRequest
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_REFERER, "http://bibhas.in");
    $body = curl_exec($ch);
    curl_close($ch);

    $json = json_decode($body);
    $twitter_result_array=object_to_array($json);
    echo count($twitter_result_array);
/*    if(count($twitter_result_array['results'])>0){
        for($i=0;$i<count($twitter_result_array['results']);$i++){

                echo "<li>" . $twitter_result_array['results'][$i]['from_user'] . ": " . $twitter_result_array['results'][$i]['text'] . "</li>";
                $user=getUserDetails($twitter_result_array['results'][$i]['from_user']);
                //storeTheTweet($twitter_result_array['results'][$i],$user);

        }
        saveLastMention("{$twitter_result_array['results'][0]['id_str']}");
    }*/
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
    $fh = fopen($myFile, 'w') or die("can't open file");
    $stringData = '<?php $last_got_mention="' . $mention_id . '"; ';
    $stringData .= " ?>";
    fwrite($fh, $stringData);
    fclose($fh);
}

function getUnixTimestamp($time,$date){
    $t_array=explode(":",$time);
    $d_array=explode("-",$date);
    $timestamp=mktime($t_array[0],$t_array[1],0,$d_array[1],$d_array[0],$d_array[2]);
    return $timestamp;
}

function storeTheTweet($tweet,$user){
    $con=mysql_connect(DB_HOST,DB_UNAME,DB_PASSWD);
    mysql_select_db("koltweeps",$con);
    $ts=time();
    mysql_query("INSERT INTO `tweets` (`id`, `from`, `text`, `timestamp`)
               VALUES (NULL, '{$tweet['from_user']}', '{$user['text']}', '{$ts}'')");
    //$error=mysql_error($con);
//    echo $error;
    mysql_close($con);

}

?>

