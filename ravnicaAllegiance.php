<?php 
    // Includes
    include_once 'simplehtmldom_1_9/simple_html_dom.php';

    // Global Variables
    $setName = "Ravnica Allegiance"; // Used for Categories in Crystal Commerce
    $tableName = "ravnicaAllegiance"; // Used for mysql
    $tcgPlayerSetURL = "https://shop.tcgplayer.com/price-guide/magic/ravnica-allegiance"; // URL to scrape
    $cardNames = array(); // Array to hold card names
    $medianPrices = array(); // Array to hold median card Prices
    $sellPrice = array(); // Array to hold our sell prices
    $buyPrice = array(); // Array to hold our buy prices
    $host = 'localhost';
    $db   = 'mtg';
    $user = 'root';
    $pass = 'root';
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    // ****** DROP TABLE IF EXISTS ******
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

    // ****** END DROP TABLE IF EXISTS *******

    // ****** CREATE TABLE ******
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
    // ****** END CREATE TABLE ******


    // ****** SCRAPE $tcgPlayerSetURL ******
        $html = file_get_html($tcgPlayerSetURL);

        $data = $html->find('.productDetail a');
        $price = $html->find('.medianPrice .cellWrapper');

        foreach ($data as $card){
            array_push($cardNames, str_replace("&#39;", "'", $card->plaintext));
        }
            
        foreach ($price as $median){
            array_push($medianPrices, str_replace("$", "", str_replace('&mdash;', '0.00', $median->plaintext)));

            $rowValue = str_replace("$", "", str_replace('&mdash;', '0.00', $median->plaintext));
            $numValue = number_format($rowValue,2);
            $theSellPrice = number_format(($numValue * .95),2);

            if ($theSellPrice >= 0.51 && $theSellPrice <= 1.99)
            {
              $theBuyPrice = number_format(0.10,2);
            }

            if ($theSellPrice >= 2.00 && $theSellPrice <= 2.99)
            {
                $theBuyPrice = number_format(0.25,2);
            }

            if ($theSellPrice <= 0.50)
            {
                $theBuyPrice = number_format(0.00,2);
            }
            if ($theSellPrice > 3)
            {
                $theBuyPrice = number_format(($theSellPrice * 0.47),2);
            }
    
            array_push($sellPrice, $theSellPrice);
            array_push($buyPrice, $theBuyPrice);
        }
            
    // ****** END SCRAPE $tcgPlayerSetURL ******

    
    // ****** INSERT INTO TABLE ******
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
    // ****** END INSERT INTO TABLE ******

    // ****** CHAIN SCRIPTS ******
    header("Location: ultimateMasters.php");
    // ****** END CHAIN SCRIPTS ******
?>