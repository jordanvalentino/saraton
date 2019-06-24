<?php

require_once __DIR__.'/vendor/autoload.php';

use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Tokenization\WhitespaceTokenizer;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\Classification\KNearestNeighbors;
use Phpml\Math\Distance\Euclidean;
use Abraham\TwitterOAuth\TwitterOAuth;

define('CONSUMER_KEY', 'Za9qeMqH1CuQtvkCquULw82Z6');
define('CONSUMER_SECRET', 'RmL7pAk6oMYsQGWWre3k3OCqaFPGqYiIZcciQ30QHRwUuPMcHH');
define('ACCESS_TOKEN', '1131743842368049153-O7UY1AWpENl3OCCKAdLlTyuaDVOrjd');
define('ACCESS_TOKEN_SECRET', '0PIkOiEqLzNQHAYAdd8VKsZ8jU8fDPKCyQkSlTZWhqtQR');

if (ini_get('memory_limit')) {
    ini_set('memory_limit', '1024M');
}
if (ini_get('max_execution_time')) {
    ini_set('max_execution_time', '300');
}

$conndb = new mysqli("localhost", "root", "", "saraton");			
$conn = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>UAS IIR Genap SARATON</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" type="text/css" href="resources/bootstrap.min.css">
</head>

<body>
    <section id="services">
        <div class="container px-5">
            <form method="POST" action="#" class="text-center mb-5">
            	<h1 class="mt-4">SARATON</h1>
            	<b>CRAWLER</b>
            	<div class="form-group mt-4">
					<input type="text" name="new" class="form-control col-8 mx-auto px-4" id="search" style="border-radius: 100px;" autofocus>
					<input type="submit" name="crawl" value="LET'S CRAWL" class="btn btn-dark form-control col-4 mt-3">
            	</div>
			</form>
				
			<?php

				function tokenize($text = "")
				{
					$unigram = explode(" ", $text);
					$bigram = array();
					for($i = 0; $i < count($unigram)-1; $i++)
					{
						$bigram[$i] = $unigram[$i]." ".$unigram[$i+1];
					}
						return array_merge($unigram, $bigram);
				}

				if(isset($_POST["new"])){

					# SEARCH TWITTER
					$query = array(
					 "q" => $_POST["new"]." -filter:retweets",
					 "count" => 100,
					 "result_type" => "recent",
					 "tweet_mode" => "extended",
					 "lang" => "id"
					);
					$tweets = $conn->get('search/tweets', $query);

					echo '<small>Returned '.count($tweets). ' results.</small>';
					foreach ($tweets->statuses as $tweet) {
						$text = preg_replace("/\r|\n/", " ", $tweet->full_text);
						$newString = preg_replace('/[ ](?=[ ])|[^@#A-Za-z0-9 ]+/i', '', $text);
						$temp = explode(" ", $newString);
						$newString = "";
						foreach ($temp as $key => $value) {
							if(substr($value, 0,1) != "@" && substr($value, 0,1) != "#" && substr($value, 0, 4) !="http"){
								$newString .= $value." ";	
							} 
						}
						// $sql = $conndb->query("SELECT tweet FROM kamus WHERE tweet='".strtolower($newString)."'");
						// if($sql->num_rows == 0) {
						// 	$sql = $conndb->query("INSERT INTO kamus(tweet,kategori) VALUES('".strtolower($newString)."','nonsara')");
						// }
						echo '<p>'.$tweet->full_text.'<br>';
						echo '<small>Posted on: <a href="https://twitter.com/'.$tweet->user->screen_name.'/status/'.$tweet->id.'">'.date('Y-m-d H:i', strtotime($tweet->created_at)).'</a></small></p>';
					}	
				}
			?>
        </div>
    </section>
	
</body>
</html>