<?php
include_once 'simplehtmldom_1_9/simple_html_dom.php';

function findSellPrice($inputPrice) {
    $floorHigh = 0.20;
    $floorLow = 0.01;
    $floorPrice = 0.24;
    $sellPercent = 0.95;
    $sellPrice = 0;

    if ($inputPrice == "-"){
        $sellPrice = "-";
    } else {
        $medianPrice = $inputPrice;
        $medianPrice = str_replace(",","", $medianPrice);
        $sellPrice = number_format($medianPrice * $sellPercent,2);
    }

    if ($sellPrice <= $floorHigh && $sellPrice >= $floorLow){
        $sellPrice = $floorPrice;
    }

    return $sellPrice;
    
}

function findBuyPrice($inputPrice, $rarity) {
    $floorCommon = "0.00";
    $floorUncommon = "0.01";
    $floorRare = "0.08";
    $floorMythic = "0.25";
    $floorToken = "0.00";
    $floorLand = "0.00";
    
    $sellPrice = $inputPrice;
    if ($sellPrice == "-"){
        $buyPrice = "-";
    }else {
        if ($sellPrice >100){
                $buyPrice =number_format((0.6*$sellPrice),2);
            }elseif($sellPrice >50){
                $buyPrice=number_format((0.55*$sellPrice),2);
            }elseif($sellPrice >25){
                $buyPrice=number_format((0.5*$sellPrice),2);
            }elseif($sellPrice >10){
                $buyPrice=number_format((0.45*$sellPrice),2);
            }elseif($sellPrice >3){
                $buyPrice=number_format((0.4*$sellPrice),2);
            }elseif($sellPrice > 2){
                $buyPrice=number_format((0.25),2);
            }
            else{
                // Common
                if ($rarity == "C")
                {
                    $buyPrice = $floorCommon;
                }
                
                // Uncommon
                if($rarity == "U")
                {
                    $buyPrice = $floorUncommon;
                }
                // Rare
                if($rarity == "R")
                {
                    $buyPrice = $floorRare;
                }
                
                // Mythic
                if ($rarity == "M")
                {
                    $buyPrice = $floorMythic;
                }
                
                // Land
                if ($rarity == "L")
                {
                    $buyPrice = $floorLand;
                }
                
                // Token
                if ($rarity == "T")
                {
                    $buyPrice = $floorToken;
                }
            }



    }
    return $buyPrice;

}

function findFoilBuyPrice($inputPrice, $rarity){

    
}

function scrapeFoils($url){
    $returnArray = array();
    $html = file_get_html($url);
    $data = $html->find('tbody tr');
    $cardNamesArray = array();
    $foilPriceArray = array();
    $rarityArray = array();
    $medianPrice = 0;
    
    foreach ($data as $card){
        array_push($cardNamesArray, str_replace("&#39;", "'", $card->first_child()->plaintext . " - Foil"));
        $medianPrice = $card->last_child()->prev_sibling()->plaintext;
        $rarity = strtoupper($card->first_child()->next_sibling()->next_sibling()->plaintext);


        // NOT GREAT
        if ($rarity == "" || $rarity == null){
            $rarity = "R";
        }

        array_push($rarityArray, $rarity);
        
        if ($medianPrice == ""){
            $medianPrice = "-";
        }
        array_push($foilPriceArray, str_replace("$", "",  $medianPrice));

    }

    // TODO
    // Scrape rarity

    for ($a = 0; $a<4;$a++)
    {
        array_pop($cardNamesArray);
        array_pop($foilPriceArray);
        array_pop($rarityArray);
    }

    // For debugging
    // for ($x = 0; $x < count($cardNamesArray); $x++){
    //     echo $cardNamesArray[$x] . " - " . $rarityArray[$x] . " - " . $foilPriceArray[$x] . "<br />";
    // }

    array_push($returnArray, $cardNamesArray);
    array_push($returnArray, $foilPriceArray);
    array_push($returnArray, $rarityArray);
    
    return $returnArray;
}

function scrapeTCG($url){
    $returnArray = array();
    $cardNamesArray = array();
    $rarityArray = array();
    $cardMedianPriceArray = array();
    $html = file_get_html($url);
    $data = $html->find('.productDetail a');
    $price = $html->find('.medianPrice .cellWrapper');
    $rarity = $html->find('.rarity .cellWrapper');

    foreach ($data as $card){
        array_push($cardNamesArray, str_replace("&#39;", "'", $card->plaintext));
    }

    foreach ($price as $median){
      array_push($cardMedianPriceArray, str_replace(" ", "", str_replace("$", "", str_replace('&mdash;', '-', $median->plaintext))));
    }

    foreach ($rarity as $oddity){
        array_push($rarityArray, str_replace(" " , "", $oddity->plaintext));
        //echo $oddity->plaintext;
    }



    array_push($returnArray, $cardNamesArray);
    array_push($returnArray, $cardMedianPriceArray);
    array_push($returnArray, $rarityArray);

    // For debugging
    // for($i=0;$i<count($returnArray[0]);$i++){
    //     echo $returnArray[0][$i] . " price: " . $returnArray[1][$i] . " | Rarity: " . $returnArray[2][$i] . "<br />";
    // }

    return $returnArray;

}

function dropTable($tableName){
    $host = 'localhost';
    $db   = 'mtg';
    $user = 'root';
    $pass = 'root';
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
    
      try{
        // Create prepared statement
        $sql = "DROP TABLE IF EXISTS $tableName";
        
        $stmt = $pdo->prepare($sql);
        
        // Execute the prepared statement
        $stmt->execute();
      } catch(PDOException $e){
        die("ERROR: Could not able to execute $sql. " . $e->getMessage());
      }
      
      // Close connection
      unset($pdo);
}

function createTable($tableName){
    $host = 'localhost';
    $db   = 'mtg';
    $user = 'root';
    $pass = 'root';
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
      
      // Attempt insert query execution
      try{

        // Create prepared statement
        $sql = "create table $tableName(
          cardId int(11) AUTO_INCREMENT not null,
          cardName varchar(70) not null,
          rarity varchar(10) not null,
          medianPrice varchar(70) not null,
          sellPrice varchar(70),
          buyPrice varchar(70),
          primary key (cardId)
          );";
        
        $stmt = $pdo->prepare($sql);
        
        // Execute the prepared statement
        $stmt->execute();
      } catch(PDOException $e){
        die("ERROR: Could not able to execute $sql. " . $e->getMessage());
      }
      
      // Close connection
      unset($pdo);
}

function insertIntoTable($tableName, $cardNames, $medianPrices, $sellPrice, $buyPrice, $rarity) {
    $host = 'localhost';
    $db   = 'mtg';
    $user = 'root';
    $pass = 'root';
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    for($x = 0; $x < count($cardNames); $x++)
    {
        //echo "Card Name: " . $cardNames[$x] . " - Median Price: " . $medianPrices[$x] . " - Sell Price: " . $sellPrice[$x] . " - Buy Price: " . $buyPrice[$x] . " - Rarity: " . $rarity[$x] . "<br />";
        /* Attempt MySQL server connection. Assuming you are running MySQL
        server with default setting (user 'root' with no password) */
        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
        
        // Attempt insert query execution
        try{
            // Create prepared statement
            $sql = "INSERT INTO $tableName (cardName, rarity, medianPrice,  sellPrice, buyPrice) VALUES (:cardName, :rarity, :medianPrice, :sellPrice, :buyPrice)";
            $stmt = $pdo->prepare($sql);
            
            // Bind parameters to statement
            $stmt->bindParam(':cardName', $cardNames[$x]);
            $stmt->bindParam(':rarity', $rarity[$x]);
            $stmt->bindParam(':medianPrice', $medianPrices[$x]);
            $stmt->bindParam(':sellPrice', $sellPrice[$x]);
            $stmt->bindParam(':buyPrice', $buyPrice[$x]);
            
            // Execute the prepared statement
            $stmt->execute();
            
        } catch(PDOException $e){
            die("ERROR: Could not able to execute $sql. " . $e->getMessage());
        }
        
        // Close connection
        unset($pdo);
    }
}

function generateSetCSV($tableName,$setName,$cardNames,$sellPrice,$buyPrice){
    $file = fopen("output/". $tableName . ".csv","w");

    fputcsv($file,array('Product Name','Category','Sell Price','Buy Price'));

    // Need to allow cards to have a , in their name
    for ($id = 0; $id < count($cardNames); $id++){
      //fputcsv($file,explode(',',$cardNames[$id] . ',' . $setName . ',' . $sellPrice[$id] . ',' . $buyPrice[$id]));
      fputcsv($file, array($cardNames[$id], $setName,$sellPrice[$id],$buyPrice[$id]));
    }
    fclose($file);
}

function generateMasterCSV($setName, $cardNames, $sellPrice, $buyPrice){
    $file = fopen("output/theMasterSheet.csv","w");

    fputcsv($file,array('Product Name','Category','Sell Price','Buy Price'));

    // Need to allow cards to have a , in their name
    for ($id = 0; $id < count($cardNames); $id++){
      //fputcsv($file,explode(',',$cardNames[$id] . ',' . $setName . ',' . $sellPrice[$id] . ',' . $buyPrice[$id]));
      fputcsv($file, array($cardNames[$id], $setName,$sellPrice[$id],$buyPrice[$id]));
    }

    fclose($file); 
}

function appendMasterCSV($setName, $cardNames, $sellPrice, $buyPrice){
    $file = fopen("output/theMasterSheet.csv","a");

    // Need to allow cards to have a , in their name
    for ($id = 0; $id < count($cardNames); $id++){
      //fputcsv($file,explode(',',$cardNames[$id] . ',' . $setName . ',' . $sellPrice[$id] . ',' . $buyPrice[$id]));
      fputcsv($file, array($cardNames[$id], $setName,$sellPrice[$id],$buyPrice[$id]));
    }

    fclose($file); 
}

function generateStandardCSV($setName, $cardNames, $sellPrice, $buyPrice){
    $file = fopen("output/formats/standard.csv","w");

    fputcsv($file,array('Product Name','Category','Sell Price','Buy Price'));

    // Need to allow cards to have a , in their name
    for ($id = 0; $id < count($cardNames); $id++){
      //fputcsv($file,explode(',',$cardNames[$id] . ',' . $setName . ',' . $sellPrice[$id] . ',' . $buyPrice[$id]));
      fputcsv($file, array($cardNames[$id], $setName,$sellPrice[$id],$buyPrice[$id]));
    }

    fclose($file); 
}

function appendStandardCSV($setName, $cardNames, $sellPrice, $buyPrice){
    $file = fopen("output/formats/standard.csv","a");

    // Need to allow cards to have a , in their name
    for ($id = 0; $id < count($cardNames); $id++){
      //fputcsv($file,explode(',',$cardNames[$id] . ',' . $setName . ',' . $sellPrice[$id] . ',' . $buyPrice[$id]));
      fputcsv($file, array($cardNames[$id], $setName,$sellPrice[$id],$buyPrice[$id]));
    }

    fclose($file); 
}

function generateModernCSV($setName, $cardNames, $sellPrice, $buyPrice){
    $file = fopen("output/formats/modern.csv","w");

    fputcsv($file,array('Product Name','Category','Sell Price','Buy Price'));

    // Need to allow cards to have a , in their name
    for ($id = 0; $id < count($cardNames); $id++){
      //fputcsv($file,explode(',',$cardNames[$id] . ',' . $setName . ',' . $sellPrice[$id] . ',' . $buyPrice[$id]));
      fputcsv($file, array($cardNames[$id], $setName,$sellPrice[$id],$buyPrice[$id]));
    }

    fclose($file); 
}

function appendModernCSV($setName, $cardNames, $sellPrice, $buyPrice){
    $file = fopen("output/formats/modern.csv","a");

    // Need to allow cards to have a , in their name
    for ($id = 0; $id < count($cardNames); $id++){
      //fputcsv($file,explode(',',$cardNames[$id] . ',' . $setName . ',' . $sellPrice[$id] . ',' . $buyPrice[$id]));
      fputcsv($file, array($cardNames[$id], $setName,$sellPrice[$id],$buyPrice[$id]));
    }

    fclose($file); 
}
?>