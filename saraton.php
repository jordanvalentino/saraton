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
    ini_set('memory_limit', '512M');
}
if (ini_get('max_execution_time')) {
    ini_set('max_execution_time', '300');
}

$conndb = new mysqli("localhost", "root", "", "saraton");			
$conn = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);

?>

<!DOCTYPE html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>UAS IIR Genap SARATON</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width">
</head>

<body>
    <section id="services">
        <div class="container">
            <div class="center gap">
                <form method="POST" action="">
				<p class="lead">Input Kata : 
					<input type="text" name="new" />
					<input type="submit" name="search" value="Search" />
				</p>
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
						//#SEARCH TWITTER
						$query = array(
						 "q" => $_POST["new"]." -filter:retweets",
						 "count" => 100,
						 "result_type" => "recent",
						 "tweet_mode"=>"extended",
						 "language"=>"id"
						);
						$tweets = $conn->get('search/tweets', $query);

						foreach ($tweets->statuses as $tweet) {
							$text = preg_replace("/\r|\n/", " ", $tweet->full_text);
							$newString = preg_replace('/[ ](?=[ ])|[^@#A-Za-z0-9 ]+/i', '', $text);
							$temp=explode(" ", $newString);
							$newString ="";
							foreach ($temp as $key => $value) {
								if(substr($value, 0,1) != "@" && substr($value, 0,1) != "#" && substr($value, 0, 4) !="http"){
									$newString .= $value." ";	
								} 
							}
							$sql = $conndb->query("SELECT tweet FROM kamus WHERE tweet='".strtolower($newString)."'");
							if($sql->num_rows == 0) {
								// $sql = $conndb->query("INSERT INTO kamus(tweet,kategori) VALUES('".strtolower($newString)."','sara')");	
							}
							echo '<p>'.$tweet->full_text.'<br>Posted on: <a href="https://twitter.com/'.$tweet->user->screen_name.'/status/'.$tweet->id.'">'.date('Y-m-d H:i', strtotime($tweet->created_at)).'</a></p>';
						}

						// # SELECT ALL TWEETS FROM DB
						// $sample_data = array();
						// $kategori = array();

						// $sql = "SELECT tweet, kategori from kamus";
						// $result = mysqli_query($conndb, $sql);
						// if ($result) 
						// {
						// 	$i=0;
						// 	while($row=mysqli_fetch_array($result))
						// 	{
						// 		$sample_data[$i] = strtolower($row["tweet"]);
						// 		$kategori[$i] = $row["kategori"];
						// 		$i++;
						// 	}		
						// } 
						// else 
						// {
						//    echo "Error: " . $sql . "" . mysqli_error($conn);
						// }

						// // $sample_data = array();
						// // $vocabulary = array();
						// // foreach ($sample_data as $key => $value) {
						// // 	$temp = explode(" ", $value);
						// // 	foreach ($temp as $key1 => $value1) {
						// // 		if(!isset($vocabulary[$value1])){
						// // 			$vocabulary[$value1]=$value1;
						// // 		}
						// // 	}
						// // }
						// // $vocabulary = array_values($vocabulary);
						// // print_r($vocabulary);
						// // foreach ($sample_data as $key1 => $value1) {
						// // 	$temp = explode(" ", $value1);
						// // 	$tempTraining = array();
						// // 	foreach ($vocabulary as $key2 => $value2) {
						// // 		$count= 0;
						// // 		foreach ($temp as $key3 => $value3) {
						// // 			if($value2===$value3) $count++;
						// // 		}
						// // 		array_push($tempTraining, $count);
						// // 	}
						// // 	array_push($sample_data, $tempTraining);
						// // }

						// # PREPARE QUERY FOR TWITTER SEARCH
						// $query = array(
						// 	"q" => $_POST["new"]." -filter:retweets",
						// 	"count" => 10,
						// 	"result_type" => "recent",
						// 	"tweet_mode"=>"extended"
						// );
						
						// # SEARCH TWITTER
						// $tweets = $conn->get('search/tweets', $query);

						// $tweet_data = array();
						// $i = 0;
						// foreach ($tweets->statuses as $tweet)
						// {
						// 	# REMOVING SPECIAL CHARACTERS
							// $text = preg_replace("/\r|\n/", " ", $tweet->full_text);
							// $temp = preg_replace('/[^@#A-Za-z0-9 ]+/i', '', $text);
							// $temp = explode(" ", $temp);

						// 	# REMOVING MENTIONS AND LINKS
						// 	$newString = "";
							// foreach ($temp as $key => $value)
							// {
							// 	if(substr($value, 0,1) != "@" && substr($value, 0,1) != "#" && substr($value, 0, 4) !="http"){
							// 		$newString .= $value." ";	
							// 	} 
							// }

						// 	$train_data = $sample_data;

						// 	# ARRAY FOR CLEAN TWEET ONLY
						// 	$train_data[] = strtolower($newString);
						// 	# ARRAY FOR ALL TWEET'S DATA
						// 	$tweet_data[$i]["tweet"] = $tweet;

						// 	# TFIDF ALL CLEAN TWEETS
						// 	$tf = new TokenCountVectorizer(new WhitespaceTokenizer());
						// 	$tf->fit($train_data);
						// 	$tf->transform($train_data);

						// 	$tfidf = new TfIdfTransformer($train_data);
						// 	$tfidf->transform($train_data);

						// 	# REMOVING NEW TWEETS
						// 	$tweet_data[$i]["tfidf"] = array_pop($train_data);

						// 	# FIT OLD TWEETS AS TRAINING DATA
						// 	$classifier = new KNearestNeighbors($k=11, new Euclidean());
						// 	$classifier->train($train_data, $kategori);

						// 	# PREDICT NEW TWEET
						// 	$hasil = $classifier->predict($tweet_data[$i]["tfidf"]);
						// 	echo $tweet_data[$i]["tweet"]->full_text. ' = '. $hasil .'<br>';

						// 	$i++;
						// }

						// echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
						// 		<script>
						// 		function myFunction(idDiv) {
						// 			var tes = idDiv;
						// 			console.log(tes);
						// 			document.getElementById(tes).style.display = "none";

						// 		}
						// 		</script>';
						
						// for ($x = 0; $x < count($tweet_data); $x++)
						// {
						// 	$tweet = $tweet_data[$x]["tweet"];
						// 	$hasil = $classifier->predict($tweet_data[$x]["tfidf"]);
						// 	echo $tweet->full_text. ' = '. $hasil .'<br>';

						// 	if($hasil == "sara")
						// 	{
			   //                  echo 
			   //                  '<div>
				  //                   <div id="warning'.$tweet->id.'" style="width:100%; position:absolute; background-color:red;">
				  //                       <p>Kalimat ini mengandung SARA </p>'.$x.'
				  //                       <button onclick="myFunction(`warning'.$tweet->id.'`)">Tampilkan</button>
				  //                   </div>		
		    //                 		<p>'.$tweet->full_text.'<br>Posted on: <a href="https://twitter.com/'.$tweet->user->screen_name.'/status/'.$tweet->id.'">'.date(`Y-m-d H:i`, strtotime($tweet->created_at)).$hasil.'</a>
		    //                 		</p>
		    //                 		<br>
		    //                 	</div>';								
						// 	}
						// 	else {
						// 		echo 
						// 		'<div>
						// 			<p>'.$tweet->full_text.'<br>Posted on: <a href="https://twitter.com/'.$tweet->user->screen_name.'/status/'.$tweet->id.'">'.date('Y-m-d H:i', strtotime($tweet->created_at)).$hasil.'</a></p>
						// 		</div>';
						// 	}
						// }

						
					}

					
				?>
            </div>
        </div>
    </section>
	
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script>

function myFunction(idDiv) {
	var tes = idDiv;
	console.log(tes);
	document.getElementById(tes).style.display = "none";

}

</script>
</html>