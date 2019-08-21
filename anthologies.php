<?php 
    // Includes
    include_once 'simplehtmldom_1_9/simple_html_dom.php';
    include_once 'includes/helper.php';
    
    // Global Variables
    $setName = "Anthologies"; // Used for Categories in Crystal Commerce
    $tableName = "anthologies"; // Used for mysql
    $tcgPlayerSetURL = "https://shop.tcgplayer.com/price-guide/magic/anthologies"; // URL to scrape
    $cardNames = array(); // Array to hold card names
    $medianPrices = array(); // Array to hold median card Prices
    $sellPrice = array(); // Array to hold our sell prices
    $buyPrice = array(); // Array to hold our buy prices
    $tcgPlayerCardDataArray = array();

      dropTable($tableName);
      createTable($tableName);

      $tcgPlayerCardDataArray = scrapeTCG($tcgPlayerSetURL);
      for ($a = 0; $a < count($tcgPlayerCardDataArray[0]); $a++){
          array_push($cardNames, $tcgPlayerCardDataArray[0][$a]);
          array_push($medianPrices, $tcgPlayerCardDataArray[1][$a]);
          $theSellPrice = findSellPrice($tcgPlayerCardDataArray[1][$a]);
          $theBuyPrice = findBuyPrice($theSellPrice);
          array_push($sellPrice, $theSellPrice);
          array_push($buyPrice, $theBuyPrice);
      }


      insertIntoTable($tableName,$cardNames, $medianPrices, $sellPrice, $buyPrice);
      generateSetCSV($tableName,$setName,$cardNames,$sellPrice,$buyPrice);
      appendMasterCSV($setName, $cardNames, $sellPrice, $buyPrice);

    // ****** CHAIN SCRIPTS ******
      header("Location: urzasSaga.php");
    // ****** END CHAIN SCRIPTS ******
?>