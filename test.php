<?php
/* update your path accordingly */
include_once 'simplehtmldom_1_9/simple_html_dom.php';

$curl = curl_init("https://shop.tcgplayer.com/price-guide/magic/modern-horizons");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
$page = curl_exec($curl);

if(curl_errno($curl)) // check for execution errors
{
	echo 'Scraper error: ' . curl_error($curl);
	exit;
}
curl_close($curl);

// Works at this point
//echo $page;
$myfile = fopen("victim.txt", "w") or die("Unable to open file!");
fwrite($myfile, $page);
fclose($myfile);

$html = file_get_contents("victim.txt");
$data = $html->find('.productDetail a');

//var_dump($html)
//$html = str_get_html($html);





//$data = $html->find('.productDetail a');
?>