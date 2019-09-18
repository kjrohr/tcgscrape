<?php 
    // Includes
    include_once 'simplehtmldom_1_9/simple_html_dom.php';
    include_once 'includes/helper.php';
    
    // Global Variables
    $setName = "From the Vault: Twenty"; // Used for Categories in Crystal Commerce
    $tableName = "fromTheVaultTwenty"; // Used for mysql
    $quietSpeculationURL = "https://www.quietspeculation.com/tradertools/prices/sets/From%20the%20Vault:%20Twenty/foil";
    $cardNames = array(); // Array to hold card names
    $medianPrices = array(); // Array to hold median card Prices
    $sellPrice = array(); // Array to hold our sell prices
    $buyPrice = array(); // Array to hold our buy prices
    $foilCardDataArray = array(); // Array to hold the 2d array that's returned from the helper function
    $tcgPlayerCardDataArray = array();
    $rarityArray = array();

      dropTable($tableName);
      createTable($tableName);


      
      $foilCardDataArray = scrapeFoils($quietSpeculationURL);
      for($i=0;$i<count($foilCardDataArray[0]);$i++){
        array_push($cardNames, str_replace(" - Foil","", $foilCardDataArray[0][$i]));
        array_push($medianPrices, $foilCardDataArray[1][$i]);
        array_push($sellPrice, findSellPrice($foilCardDataArray[1][$i]));
        array_push($buyPrice, findBuyPrice($foilCardDataArray[1][$i], $foilCardDataArray[2][$i]));
        array_push($rarityArray, $foilCardDataArray[2][$i]);
    }

      insertIntoTable($tableName,$cardNames, $medianPrices, $sellPrice, $buyPrice, $rarityArray);
      generateSetCSV($tableName,$setName,$cardNames,$sellPrice,$buyPrice);
      appendMasterCSV($setName, $cardNames, $sellPrice, $buyPrice);
      appendFTVCSV($tableName,$setName,$cardNames,$sellPrice,$buyPrice);

    // ****** CHAIN SCRIPTS ******
      header("Location: index.php");
    // ****** END CHAIN SCRIPTS ******
?>