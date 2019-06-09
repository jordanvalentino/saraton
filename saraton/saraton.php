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
				<p class="lead">Input Kalimat : <textarea rows="5" name="new" ></textarea> <input type="submit" name="search" value="Check">
				</p>
				</form>
				
				<?php
					require_once __DIR__.'/vendor/autoload.php';

					use Phpml\FeatureExtraction\TokenCountVectorizer;
					use Phpml\Tokenization\WhitespaceTokenizer;
					use Phpml\FeatureExtraction\TfIdfTransformer;
					use Phpml\Classification\KNearestNeighbors;
					use Phpml\Math\Distance\Euclidean;
					use Abraham\TwitterOAuth\TwitterOAuth;


					function tokenize($text = "")
					{
						$unigram = explode(" ", $text);
						$bigram = array();
						// for($i = 0; $i < count($unigram)-1; $i++)
						// {
						// 	$bigram[$i] = $unigram[$i]." ".$unigram[$i+1];
						// }
  						return array_merge($unigram, $bigram);
					}
					if(isset($_POST["new"])){
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
						// $query = array(
						//  "q" => $_POST["new"]." -filter:retweets",
						//  "count" => 200,
						//  "result_type" => "recent",
						//  "tweet_mode"=>"extended"
						// );
						// $tweets = $conn->get('search/tweets', $query);

						// foreach ($tweets->statuses as $tweet) {
						// 	$newString = preg_replace('/[ ](?=[ ])|[^@A-Za-z0-9 ]+/i', '', $tweet->full_text);
						// 	$temp=explode(" ", $newString);
						// 	$newString ="";
						// 	foreach ($temp as $key => $value) {
						// 		if(substr($value, 0,1) != "@" && substr($value, 0, 4) !="http"){
						// 			$newString .= $value." ";	
						// 		} 
						// 	}
						// 	$sql = "INSERT INTO kamus(tweet,kategori) VALUES('".$newString."','sara')";
						// 	$result = mysqli_query($conndb, $sql);
						// 	echo '<p>'.$tweet->full_text.'<br>Posted on: <a href="https://twitter.com/'.$tweet->user->screen_name.'/status/'.$tweet->id.'">'.date('Y-m-d H:i', strtotime($tweet->created_at)).'</a></p>';
						// }



						$sql = "SELECT tweet, kategori from kamus";
						$result = mysqli_query($conndb, $sql);						
						$sample_data = array();
						$kategori = array();
						if ($result) {
							$i=0;
							while($row=mysqli_fetch_array($result))
							{
								$kategori[$i] = $row["kategori"];
								$sample_data[$i] = $row["tweet"];
								$i++;
							}		
						} else {
						   echo "Error: " . $sql . "" . mysqli_error($conn);
						}

						// $sample_data = array();
						// $vocabulary = array();
						// foreach ($sample_data as $key => $value) {
						// 	$temp = explode(" ", $value);
						// 	foreach ($temp as $key1 => $value1) {
						// 		if(!isset($vocabulary[$value1])){
						// 			$vocabulary[$value1]=$value1;
						// 		}
						// 	}
						// }
						// $vocabulary = array_values($vocabulary);
						// print_r($vocabulary);
						// foreach ($sample_data as $key1 => $value1) {
						// 	$temp = explode(" ", $value1);
						// 	$tempTraining = array();
						// 	foreach ($vocabulary as $key2 => $value2) {
						// 		$count= 0;
						// 		foreach ($temp as $key3 => $value3) {
						// 			if($value2===$value3) $count++;
						// 		}
						// 		array_push($tempTraining, $count);
						// 	}
						// 	array_push($sample_data, $tempTraining);
						// }

						
						
						
						$query = array(
						 "q" => $_POST["new"]." -filter:retweets",
						 "count" => 100,
						 "result_type" => "recent",
						 "tweet_mode"=>"extended"
						);
						
						$tweets = $conn->get('search/tweets', $query);
						$tweet_data = array();
						$i=0;
						foreach ($tweets->statuses as $tweet) {
							$temp = preg_replace('/[^@A-Za-z0-9 ]+/i', '', $tweet->full_text);
							$temp = explode(" ", $temp);
							$newString = "";
							foreach ($temp as $key => $value) {
								if(substr($value, 0,1) != "@" && substr($value, 0,4) != "http"){
									$newString .= $value." ";
								}
							}
							array_push($sample_data, strtolower($newString));	
							$tweet_data[$i]["tweet"] = $tweet;	
							$i++;
						}

						$tf = new TokenCountVectorizer(new WhitespaceTokenizer());
						$tf->fit($sample_data);
						$tf->transform($sample_data);

						$tfidf = new TfIdfTransformer($sample_data);
						$tfidf->transform($sample_data);

						for ($i=count($tweet_data)-1; $i >= 0; $i--) { 
							$tweet_data[$i]["tfidf"] = array_pop($sample_data);
						}

						$classifier = new KNearestNeighbors($k=11, new Euclidean());
						$classifier->train($sample_data, $kategori);

						echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
								<script>
								function myFunction(idDiv) {
									var tes = idDiv;
									console.log(tes);
									document.getElementById(tes).style.display = "none";

								}
								</script>';	
						for ($x=0; $x < count($tweet_data); $x++) {
							$tweet = $tweet_data[$x]["tweet"];
							$hasil =$classifier->predict($tweet_data[$x]["tfidf"]);
							if($hasil === "sara"){
			                    echo '<div>
					                    <div id="warning'.$tweet->id.'" style="width:100%; position:absolute; background-color:red;">
					                        <p>Kalimat ini mengandung SARA </p>'.$x.'
					                        <button onclick="myFunction(`warning'.$tweet->id.'`)">Tampilkan</button>
					                    </div>		
			                    		<p>'.$tweet->full_text.'<br>Posted on: <a href="https://twitter.com/'.$tweet->user->screen_name.'/status/'.$tweet->id.'">'.date(`Y-m-d H:i`, strtotime($tweet->created_at)).$hasil.'</a>
			                    		</p>
			                    		<br>
			                    	</div>';								
							}
							else {
								echo '<div>
										<p>'.$tweet->full_text.'<br>Posted on: <a href="https://twitter.com/'.$tweet->user->screen_name.'/status/'.$tweet->id.'">'.date('Y-m-d H:i', strtotime($tweet->created_at)).$hasil.'</a></p>
									  </div>';
							}
						}

						
					}

					
					?>
            </div>
        </div>
    </section>
	
</body>
</html>