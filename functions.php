<?php
/*
 *      functions.php
 */
//$stopWords = array("a", "about", "above", "above", "across", "after", "afterwards", "again", "against", "all", "almost", "alone", "along", "already", "also","although","always","am","among", "amongst", "amoungst", "amount",  "an", "and", "another", "any","anyhow","anyone","anything","anyway", "anywhere", "are", "around", "as",  "at", "back","be","became", "because","become","becomes", "becoming", "been", "before", "beforehand", "behind", "being", "below", "beside", "besides", "between", "beyond", "bill", "both", "bottom","but", "by", "call", "can", "cannot", "cant", "co", "con", "could", "couldnt", "cry", "de", "describe", "detail", "do", "done", "down", "due", "during", "each", "eg", "eight", "either", "eleven","else", "elsewhere", "empty", "enough", "etc", "even", "ever", "every", "everyone", "everything", "everywhere", "except", "few", "fifteen", "fify", "fill", "find", "fire", "first", "five", "for", "former", "formerly", "forty", "found", "four", "from", "front", "full", "further", "get", "give", "go", "had", "has", "hasnt", "have", "he", "hence", "her", "here", "hereafter", "hereby", "herein", "hereupon", "hers", "herself", "him", "himself", "his", "how", "however", "hundred", "ie", "if", "in", "inc", "indeed", "interest", "into", "is", "it", "its", "itself", "keep", "last", "latter", "latterly", "least", "less", "ltd", "made", "many", "may", "me", "meanwhile", "might", "mill", "mine", "more", "moreover", "most", "mostly", "move", "much", "must", "my", "myself", "name", "namely", "neither", "never", "nevertheless", "next", "nine", "no", "nobody", "none", "noone", "nor", "not", "nothing", "now", "nowhere", "of", "off", "often", "on", "once", "one", "only", "onto", "or", "other", "others", "otherwise", "our", "ours", "ourselves", "out", "over", "own","part", "per", "perhaps", "please", "put", "rather", "re", "same", "see", "seem", "seemed", "seeming", "seems", "serious", "several", "she", "should", "show", "side", "since", "sincere", "six", "sixty", "so", "some", "somehow", "someone", "something", "sometime", "sometimes", "somewhere", "still", "such", "system", "take", "ten", "than", "that", "the", "their", "them", "themselves", "then", "thence", "there", "thereafter", "thereby", "therefore", "therein", "thereupon", "these", "they", "thickv", "thin", "third", "this", "those", "though", "three", "through", "throughout", "thru", "thus", "to", "together", "too", "top", "toward", "towards", "twelve", "twenty", "two", "un", "under", "until", "up", "upon", "us", "very", "via", "was", "we", "well", "were", "what", "whatever", "when", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein", "whereupon", "wherever", "whether", "which", "while", "whither", "who", "whoever", "whole", "whom", "whose", "why", "will", "with", "within", "without", "would", "yet", "you", "your", "yours", "yourself", "yourselves", "the");
$stopWords =array();
$file_handle = fopen("stopwordlist.csv", "r");
while (!feof($file_handle) ) {
	$line_of_text = fgetcsv($file_handle, 1024);
	array_push($stopWords, $line_of_text[0]);
}
fclose($file_handle);
echo count($stopWords);


function get_keywords_from_pool($pool){
	global $stopWords;
	$pool = just_clean($pool);
	$word_array=explode(" ", $pool);
	$refined_word_array = array();
	foreach($word_array as $word){
		if(!in_array($word, $stopWords) && !empty($word))
			array_push($refined_word_array, $word);
	}
	return $refined_word_array;
}

function just_clean($string)
{
// Replace other special chars
$specialCharacters = array(
'#' => '',
'$' => '',
'%' => '',
'&' => '',
'@' => '',
'.' => '',
'€' => '',
'+' => '',
'=' => '',
'§' => '',
'\\' => '',
'/' => ''
);

while (list($character, $replacement) = each($specialCharacters)) {
$string = str_replace($character, '-' . $replacement . '-', $string);
}

$string = strtr($string,
"ÀÁÂÃÄÅáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ",
"AAAAAAaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn"
);

// Remove all remaining other unknown characters
$string = preg_replace('/[^a-zA-Z0-9-]/', ' ', $string);
$string = preg_replace('/^[-]+/', '', $string);
$string = preg_replace('/[-]+$/', '', $string);
$string = preg_replace('/[-]{2,}/', '', $string);

return $string;
}

function extractCommonWords($string){
	global $stopWords;
	$string = preg_replace('/http:\/\/[a-zA-Z0-9-\_\+\=\?\/\.\&\*\%\$\#\!\@]+/', '', $string);
		$string = preg_replace('/@[a-zA-Z0-9]+/','',$string);
      $string = preg_replace('/sss+/i', '', $string);
      $string = trim($string); // trim the string
      $string = preg_replace('/[^a-zA-Z0-9 -]/', '', $string); // only take alphanumerical characters, but keep the spaces and dashes too…
      //$string = strtolower($string); // make it lowercase

      preg_match_all('/\b.*?\b/i', $string, $matchWords);
      $matchWords = $matchWords[0];

      foreach ( $matchWords as $key=>$item ) {
		  $item=trim($item);
          if ( $item == '' || in_array(strtolower($item), $stopWords) || strlen($item) < 3 ) {
              unset($matchWords[$key]);
          }
      }
      $wordCountArr = array();
      if ( is_array($matchWords) ) {
          foreach ( $matchWords as $key => $val ) {
              //$val = strtolower($val);
              if ( isset($wordCountArr[$val]) ) {
                  $wordCountArr[$val]++;
              } else {
                  $wordCountArr[$val] = 1;
              }
          }
      }
      arsort($wordCountArr);
      $wordCountArr = array_slice($wordCountArr, 0, 10);
      return $wordCountArr;
}
?>
