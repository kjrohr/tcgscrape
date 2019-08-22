<?php 
    // Includes
    include_once 'simplehtmldom_1_9/simple_html_dom.php';
    include_once 'includes/helper.php';
    
    // Global Variables
    $setName = "Commander's Arsenal"; // Used for Categories in Crystal Commerce
    $tableName = "commandersArsenal"; // Used for mysql
    $quietSpeculationURL = "https://www.quietspeculation.com/tradertools/prices/sets/Commander's%20Arsenal/foil";
    $cardNames = array(); // Array to hold card names
    $medianPrices = array(); // Array to hold median card Prices
    $sellPrice = array(); // Array to hold our sell prices
    $buyPrice = array(); // Array to hold our buy prices
    $foilCardDataArray = array(); // Array to hold the 2d array that's returned from the helper function
    $tcgPlayerCardDataArray = array();

      dropTable($tableName);
      createTable($tableName);

      $foilCardDataArray = scrapeFoils($quietSpeculationURL);
      for($i=0;$i<count($foilCardDataArray[0]);$i++){
        array_push($cardNames, str_replace(" - Foil","", $foilCardDataArray[0][$i]));
        array_push($medianPrices, $foilCardDataArray[1][$i]);
        array_push($sellPrice, $foilCardDataArray[1][$i]);
        array_push($buyPrice, findBuyPrice($foilCardDataArray[1][$i]));
    }

      insertIntoTable($tableName,$cardNames, $medianPrices, $sellPrice, $buyPrice);
      generateSetCSV($tableName,$setName,$cardNames,$sellPrice,$buyPrice);
      appendMasterCSV($setName, $cardNames, $sellPrice, $buyPrice);

    // ****** CHAIN SCRIPTS ******
      header("Location: commander2013.php");
    // ****** END CHAIN SCRIPTS ******
?>