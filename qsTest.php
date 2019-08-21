<?php
    include_once 'simplehtmldom_1_9/simple_html_dom.php';
    include_once 'includes/helper.php';
    // Global Variables
    $setName = "Core Set 2020"; // Used for Categories in Crystal Commerce
    $tableName = "coreSet2020"; // Used for mysql
    $tcgPlayerSetURL = "https://www.quietspeculation.com/tradertools/prices/sets/Core%20Set%202020/foil"; // URL to scrape

    $html = file_get_html($tcgPlayerSetURL);
    $data = $html->find('tbody tr');
    $price = $html->find('tbody tr .text-right');
    $tableDataArray = array();
    $foilPriceArray = array();
    $cardNamesArray = array();

    foreach ($data as $card){
        // array_push($cardNamesArray, str_replace("&#39;", "'", $card->plaintext));
        //echo str_replace("&#39;", "'", $card->plaintext) . "<br />";
        // This does it
        // echo $card->first_child()->plaintext . "<br />";
        array_push($cardNamesArray, str_replace("&#39;", "'", $card->first_child()->plaintext));
        echo $card->first_child()->plaintext . " price: " .  $card->last_child()->prev_sibling()->plaintext . "<br />";
        //echo $card->first_child()->plaintext . " price: " . $card->lastplaintext . "<br />";
    }
    
    // foreach ($price as $median){
    //     array_push($tableDataArray, str_replace("$", "", str_replace('&mdash;', '0.00', $median->plaintext)));
    //   }
    //   $counter = 0;
    //   for ($i = 0; $i < count($tableDataArray); $i+=5)
    //   {
          
    //       //echo  $counter . ": " . $foilPriceArray[$i] . "<br />";
    //       $foilPriceArray[$counter] = $tableDataArray[$i];
    //       $counter++;
    //   }

    // //   for ($x = 0; $x < count($foilPriceArray); $x++)
    // //   {
    // //       echo $x . ": " . $foilPriceArray[$x] . "<br />";
    // //   }

       echo "cardNamesArray count: " . count($cardNamesArray) . "<br />";
    //   echo "foilPriceArray count: " . count($foilPriceArray) . "<br />";
?> 