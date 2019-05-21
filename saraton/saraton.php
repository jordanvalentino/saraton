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
						$conn = new mysqli("localhost", "root", "", "saraton");
						$sql = "SELECT * FROM kamus";
						$result = mysqli_query($conn, $sql);
						$sample_data = array();
						$kategori = array();
						if ($result) {
							$i=0;
							while($row=mysqli_fetch_array($result))
							{
								$kategori[$i] = $row["kategori"];
								$sample_data[$i] = $row["kalimat"];
								$i++;
							}		
							$sample_data[$i] = $_POST["new"];          	
						} else {
						   echo "Error: " . $sql . "" . mysqli_error($conn);
						}

						$training_data = array();
						$vocabulary = array();
						foreach ($sample_data as $key => $value) {
							$temp = tokenize($value);
							foreach ($temp as $key => $value) {
								if(in_array($value, $vocabulary)){

								}
								else{
									array_push($vocabulary, $value);
								}
							}
						}

						foreach ($sample_data as $key1 => $value1) {
							$temp = tokenize($value1);
							$tempTraining = array();
							foreach ($vocabulary as $key2 => $value2) {
								$count= 0;
								foreach ($temp as $key3 => $value3) {
									if($value2==$value3) $count++;
								}
								array_push($tempTraining, $count);
							}
							array_push($training_data, $tempTraining);
						}

						print_r($vocabulary);
						print_r($training_data);
						$tfidf = new TfIdfTransformer($training_data);
						$tfidf->transform($training_data);
						$i=0;


						$data_baru = $_POST["new"];
						$classifier = new KNearestNeighbors($k=11, new Euclidean());
						array_pop($training_data);
						$classifier->train($training_data, $kategori);
						$hasil =$classifier->predict($data_baru);
						echo "<b><u>Hasil Prediksi Kategori Berita Baru adalah ".$hasil."</u></b>";
					}

					
					?>
            </div>
        </div>
    </section>
	
</body>
</html>