<?php
include_once 'simplehtmldom_1_9/simple_html_dom.php';

function findSellPrice($inputPrice) {
    $medianPrice = $inputPrice;
    $medianPrice = str_replace(",","", $medianPrice);
    $sellPrice = number_format($medianPrice * 0.95,2);

    if ($sellPrice <= 0.20)
    {
        $sellPrice = "0.20";
    }

    return $sellPrice;
    
}

function findBuyPrice($inputPrice) {
    //=if(isblank(D3),,if(or(D3="—",D3="Market Price"),"—",if(or(B3="C",B3="U",B3="T",B3="L",B3="P"),if(D3>0.99,mround(D3*0.35,0.1),if(D3>0.49,mround(D3*0.1,0.05),if(D3="U",0.01,0)))
    // ,if(and(or(B3="R",B3="M"),D3<2),if(B3="R",0.08,0.25),if(D3<3,mround(0.2*D3,0.05),if(D3<10,mround(0.4*D3,0.1),if(D3<25,mround(0.45*D3,0.25),if(D3<50,mround(0.5*D3,0.25),if(D3<100,mround(0.55*D3,0.5),mround(0.6*D3,0.5))))))))))
    $sellPrice = $inputPrice;
    $sellPrice = str_replace(",","", $sellPrice);
    
    if ($sellPrice >= 0.51 && $sellPrice <= 1.99)
    {
      $buyPrice = number_format(0.08,2);
    }
    
    if ($sellPrice >= 2.00 && $sellPrice <= 2.99)
    {
        $buyPrice = number_format(0.25,2);
    }
    
    if ($sellPrice <= 0.50)
    {
        $buyPrice = number_format(0.00,2);
    }

    if ($sellPrice >= 3)
    {
        $buyPrice = number_format(($sellPrice * 0.47),2);
    }

    if($sellPrice >= 100.00)
    {
        $buyPrice = number_format(($sellPrice * 0.6),2);
    }
    return $buyPrice;


    // Common

    // Uncommon

    // Rare

    // Mythic

}

function scrapeFoils($url){
    $returnArray = array();
    $html = file_get_html($url);
    $data = $html->find('tbody tr');
    $cardNamesArray = array();
    $foilPriceArray = array();
    $medianPrice = 0;
    
    foreach ($data as $card){
        array_push($cardNamesArray, str_replace("&#39;", "'", $card->first_child()->plaintext . " - Foil"));
        $medianPrice = $card->last_child()->prev_sibling()->plaintext;
        if ($medianPrice == ""){
            $medianPrice = "0.00";
        }
        array_push($foilPriceArray, str_replace("$", "", str_replace('&mdash;', '0.00', $medianPrice)));

    }

    for ($a = 0; $a<4;$a++)
    {
        array_pop($cardNamesArray);
        array_pop($foilPriceArray);
    }

    array_push($returnArray, $cardNamesArray);
    array_push($returnArray, $foilPriceArray);
    
    return $returnArray;
}

function scrapeTCG($url){
    $returnArray = array();
    $cardNamesArray = array();
    $cardMedianPriceArray = array();
    $html = file_get_html($url);
    $data = $html->find('.productDetail a');
    $price = $html->find('.medianPrice .cellWrapper');

    foreach ($data as $card){
        array_push($cardNamesArray, str_replace("&#39;", "'", $card->plaintext));
    }

    // TODO
    // one of the str_replaces will probably need to be removed
    foreach ($price as $median){
      array_push($cardMedianPriceArray, str_replace(" ", "", str_replace("$", "", str_replace('&mdash;', '0.00', $median->plaintext))));
    }

    array_push($returnArray, $cardNamesArray);
    array_push($returnArray, $cardMedianPriceArray);

    // For debugging
    // for($i=0;$i<count($returnArray[0]);$i++){
    //     echo $returnArray[0][$i] . " price: " . $returnArray[1][$i] . "<br />";
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

function insertIntoTable($tableName, $cardNames, $medianPrices, $sellPrice, $buyPrice) {
    $host = 'localhost';
    $db   = 'mtg';
    $user = 'root';
    $pass = 'root';
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    for($x = 0; $x < count($cardNames); $x++)
    {
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
            $sql = "INSERT INTO $tableName (cardName, medianPrice, sellPrice, buyPrice) VALUES (:cardName, :medianPrice, :sellPrice, :buyPrice)";
            $stmt = $pdo->prepare($sql);
            
            // Bind parameters to statement
            $stmt->bindParam(':cardName', $cardNames[$x]);
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
?>