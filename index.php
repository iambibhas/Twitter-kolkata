<?php
/*
 *      index.php
 *
 *
 */
include 'config.php';
include 'functions.php';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Twitter Kolkata</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 0.20" />
	<link rel="stylesheet" href="style.css" />
	<link href="jquery.tweet.css" media="all" rel="stylesheet" type="text/css" />
	<script language="javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.1/jquery.min.js" type="text/javascript"></script>
	<script language="javascript" src="jquery.tweet.js" type="text/javascript"></script>
	<script type='text/javascript'>
    $(document).ready(function(){
        $("#tweet-list").tweet({
            avatar_size: 32,
			count: 20,
			username: "iAmBivas",
			list: "myKolkata",
			loading_text: "loading list...",
			refresh_interval: 60
        });
    });
</script>
</head>

<body>
<div id="header">
	<h1>Twitter Kolkata</h1>
</div>
<div id="trend">
	<div id="trend-title">Current Trend Words</div>
	<?php
		$top_trends=get_top_trends();
		foreach($top_trends as $keyword){
			if(strlen($keyword['keyword'])>=3){
				$font_size=12+$keyword['frequency'];
				?>
				<span style="padding: 0 10px; font-size:<?php echo $font_size; ?>px" ><?php echo $keyword['keyword']; ?></span>
				<?php
			}
		}
	?>
</div>
<div id="tweet-list"></div>
</body>

</html>
