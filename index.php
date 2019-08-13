<?php
 
/* update your path accordingly */
include_once 'simplehtmldom_1_9/simple_html_dom.php';


 
$url = "https://shop.tcgplayer.com/price-guide/magic/modern-horizons";
 
$html = file_get_html($url);
// $myfile = fopen("test.txt", "w") or die("Unable to open file!");
// fwrite($myfile, $html);
// fclose($myfile);

/*
Get all table rows having the id attribute named 'rhsline'.
As the list of sponsored links is in the 'ol' tag; as can be
seen from the DOM tree above; we use the 'children' function
on the $data object to get the sponsored links.
*/

// tbody tr
$data = $html->find('.productDetail a');
$price = $html->find('.medianPrice .cellWrapper');

$cardNames = array();
$medianPrices = array();
foreach ($data as $card)
  array_push($cardNames, $card->plaintext);

foreach ($price as $median)
  array_push($medianPrices, $median);

for ($i = 0; $i < count($cardNames); $i++ )
{
  echo $cardNames[$i] . ": " . $medianPrices[$i];
}

// $myfile = fopen("test2.txt", "w") or die("Unable to open file!");
// fwrite($myfile, $data);
// fclose($myfile);

 $counter = 0;
/*
  Make sure that sponsors ads are available,
  Some keywords do not have sponsor ads.
*/

/* Product Name 
   td class product
   div class cellWrapper
   div class productDetail
*/ 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>

<table>
  <thead>
    <tr>
      <td>Card Name</td>
      <td>Median Price</td>
  </tr>
  </thead>
  <tbody>
<!-- <?php 
foreach($data as $card)
  echo "<tr><td>" . $card->plaintext . "</td></tr>";
?> -->
</tbody>
</table>
</body>
</html>


    <?php
    // foreach($card as $info)
      // Next line works to a certain extent
      //echo $info;

    //echo $card->find('div div', 0)->plaintext . $counter . "<br>";

 
?>
