<?php 
    // Includes
    include_once 'simplehtmldom_1_9/simple_html_dom.php';
    include_once 'includes/helper.php';
    
    // Global Variables
    $setName = "Core Set 2019"; // Used for Categories in Crystal Commerce
    $tableName = "coreSet2019"; // Used for mysql
    $tcgPlayerSetURL = "https://shop.tcgplayer.com/price-guide/magic/core-set-2019"; // URL to scrape
    $quietSpeculationURL = "https://www.quietspeculation.com/tradertools/prices/sets/Core%202019/foil";
    $cardNames = array(); // Array to hold card names
    $medianPrices = array(); // Array to hold median card Prices
    $sellPrice = array(); // Array to hold our sell prices
    $buyPrice = array(); // Array to hold our buy prices
    $foilCardDataArray = array(); // Array to hold the 2d array that's returned from the helper function
    $tcgPlayerCardDataArray = array();
    $rarityArray = array();

      dropTable($tableName);
      createTable($tableName);

      $tcgPlayerCardDataArray = scrapeTCG($tcgPlayerSetURL);
      for ($a = 0; $a < count($tcgPlayerCardDataArray[0]); $a++){
          array_push($cardNames, $tcgPlayerCardDataArray[0][$a]);
          array_push($medianPrices, $tcgPlayerCardDataArray[1][$a]);
          $theSellPrice = findSellPrice($tcgPlayerCardDataArray[1][$a]);
          $theBuyPrice = findBuyPrice($theSellPrice, $tcgPlayerCardDataArray[2][$a]);
          array_push($sellPrice, $theSellPrice);
          array_push($buyPrice, $theBuyPrice);
          array_push($rarityArray, $tcgPlayerCardDataArray[2][$a]);
      }
      
      $foilCardDataArray = scrapeFoils($quietSpeculationURL);
      for($i=0;$i<count($foilCardDataArray[0]);$i++){
        array_push($cardNames, $foilCardDataArray[0][$i]);
        array_push($medianPrices, $foilCardDataArray[1][$i]);
        array_push($sellPrice, findSellPrice($foilCardDataArray[1][$i]));
        array_push($buyPrice, findBuyPrice($foilCardDataArray[1][$i], $foilCardDataArray[2][$i]));
        array_push($rarityArray, $foilCardDataArray[2][$i]);
    }

      insertIntoTable($tableName,$cardNames, $medianPrices, $sellPrice, $buyPrice, $rarityArray);
      generateSetCSV($tableName,$setName,$cardNames,$sellPrice,$buyPrice);
      appendMasterCSV($setName, $cardNames, $sellPrice, $buyPrice);
      appendStandardCSV($setName,$cardNames,$sellPrice,$buyPrice);
      appendModernCSV($setName, $cardNames, $sellPrice, $buyPrice);

    // ****** CHAIN SCRIPTS ******
      header("Location: index.php");
    // ****** END CHAIN SCRIPTS ******
?>