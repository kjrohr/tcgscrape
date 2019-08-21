<?php
    include_once 'simplehtmldom_1_9/simple_html_dom.php';
    include_once 'includes/helper.php';
    // // Global Variables
    // $setName = "Core Set 2020"; // Used for Categories in Crystal Commerce
    // $tableName = "coreSet2020"; // Used for mysql
     $url = "https://www.quietspeculation.com/tradertools/prices/sets/Modern%20Horizons/foil"; // URL to scrape

    // $html = file_get_html($tcgPlayerSetURL);
    // $data = $html->find('tbody tr');
    // $foilPriceArray = array();
    // $cardNamesArray = array();

    // foreach ($data as $card){
    //     array_push($cardNamesArray, str_replace("&#39;", "'", $card->first_child()->plaintext));
    //     array_push($foilPriceArray, str_replace("$", "", str_replace('&mdash;', '0.00', $card->last_child()->prev_sibling()->plaintext)));
    // }

    // for ($a = 0; $a<4;$a++)
    // {
    //     array_pop($cardNamesArray);
    //     array_pop($foilPriceArray);
    // }
    
    $foilCardDataArray = scrapeFoils($url);

    for($i=0;$i<count($foilCardDataArray[0]);$i++){
        echo $foilCardDataArray[0][$i] . " price: " .  $foilCardDataArray[1][$i]."<br />";
    }

    echo count($foilCardDataArray[0]);
?> 