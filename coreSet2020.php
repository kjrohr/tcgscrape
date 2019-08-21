<?php 
    // Includes
    include_once 'simplehtmldom_1_9/simple_html_dom.php';
    include_once 'includes/helper.php';
    // Global Variables
    $setName = "Core Set 2020"; // Used for Categories in Crystal Commerce
    $tableName = "coreSet2020"; // Used for mysql
    $tcgPlayerSetURL = "https://shop.tcgplayer.com/price-guide/magic/core-set-2020"; // URL to scrape
    $quietSpeculationURL = "https://www.quietspeculation.com/tradertools/prices/sets/Core%20Set%202020/foil"; // URL to scrape
    $cardNames = array(); // Array to hold card names
    $medianPrices = array(); // Array to hold median card Prices
    $sellPrice = array(); // Array to hold our sell prices
    $buyPrice = array(); // Array to hold our buy prices
    $foilCardDataArray = array(); // Array to hold the 2d array that's returned from the helper function
    $tcgPlayerCardDataArray = array();

    // ****** DROP TABLE IF EXISTS ******
      dropTable($tableName);

    // ****** END DROP TABLE IF EXISTS *******

    // ****** CREATE TABLE ******
      createTable($tableName);
    // ****** END CREATE TABLE ******
    
    // ****** SCRAPE $tcgPlayerSetURL ******
        $tcgPlayerCardDataArray = scrapeTCG($tcgPlayerSetURL);

      for ($a = 0; $a < count($tcgPlayerCardDataArray[0]); $a++){
            //echo $tcgPlayerCardDataArray[0][$a] . " price: " . $tcgPlayerCardDataArray[1][$a] . "<br />";
          array_push($cardNames, $tcgPlayerCardDataArray[0][$a]);
          array_push($medianPrices, $tcgPlayerCardDataArray[1][$a]);
          $theSellPrice = findSellPrice($tcgPlayerCardDataArray[1][$a]);
          $theBuyPrice = findBuyPrice($theSellPrice);
          array_push($sellPrice, $theSellPrice);
          array_push($buyPrice, $theBuyPrice);
      }
            
    // ****** END SCRAPE $tcgPlayerSetURL ******


    // ****** SCRAPE QUIET SPECULATION ******

      $foilCardDataArray = scrapeFoils($quietSpeculationURL);

      for($i=0;$i<count($foilCardDataArray[0]);$i++){
        array_push($cardNames, $foilCardDataArray[0][$i]);
        array_push($medianPrices, $foilCardDataArray[1][$i]);
        array_push($sellPrice, $foilCardDataArray[1][$i]);
        array_push($buyPrice, findBuyPrice($foilCardDataArray[1][$i]));
    }

    // ****** END SCRAPE QUIET SPECULATION ******

    // ****** INSERT INTO TABLE ******
      insertIntoTable($tableName,$cardNames, $medianPrices, $sellPrice, $buyPrice);
    // ****** END INSERT INTO TABLE ******

    // ****** GENERATE CSV ******
      generateSetCSV($tableName,$setName,$cardNames,$sellPrice,$buyPrice);
    // ****** END GENERATE CSV ******

    // ****** GENERATE MASTER CSV ******
      generateMasterCSV($setName, $cardNames, $sellPrice, $buyPrice);
      // ****** END GENERATE MASTER CSV ******

    // ****** CHAIN SCRIPTS ******
    // modernHorizons.php is next
    header("Location: index.php");
    // ****** END CHAIN SCRIPTS ******

?>