<?php 
    // Includes
    include_once 'simplehtmldom_1_9/simple_html_dom.php';
    include_once 'includes/helper.php';
    
    // Global Variables
    $setName = "Nemesis"; // Used for Categories in Crystal Commerce
    $tableName = "nemesis"; // Used for mysql
    $tcgPlayerSetURL = "https://shop.tcgplayer.com/price-guide/magic/nemesis"; // URL to scrape
    $quietSpeculationURL = "https://www.quietspeculation.com/tradertools/prices/sets/Nemesis/foil";
    $cardNames = array(); // Array to hold card names
    $medianPrices = array(); // Array to hold median card Prices
    $sellPrice = array(); // Array to hold our sell prices
    $buyPrice = array(); // Array to hold our buy prices
    $foilCardDataArray = array(); // Array to hold the 2d array that's returned from the helper function
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
      
      $foilCardDataArray = scrapeFoils($quietSpeculationURL);
      for($i=0;$i<count($foilCardDataArray[0]);$i++){
        array_push($cardNames, $foilCardDataArray[0][$i]);
        array_push($medianPrices, $foilCardDataArray[1][$i]);
        array_push($sellPrice, $foilCardDataArray[1][$i]);
        array_push($buyPrice, findBuyPrice($foilCardDataArray[1][$i]));
    }

      insertIntoTable($tableName,$cardNames, $medianPrices, $sellPrice, $buyPrice);
      generateSetCSV($tableName,$setName,$cardNames,$sellPrice,$buyPrice);
      appendMasterCSV($setName, $cardNames, $sellPrice, $buyPrice);
      appendModernCSV($setName, $cardNames, $sellPrice, $buyPrice);

    // ****** CHAIN SCRIPTS ******
      header("Location: index.php");
    // ****** END CHAIN SCRIPTS ******
?>