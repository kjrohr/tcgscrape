<?php 
    // Includes
    include_once 'simplehtmldom_1_9/simple_html_dom.php';

    // Global Variables
    $setName = ""; // Used for Categories in Crystal Commerce
    $tableName = "coreSet2020"; // Used for mysql
    $tcgPlayerSetURL = "https://shop.tcgplayer.com/price-guide/magic/core-set-2020"; // URL to scrape
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
            array_push($medianPrices, str_replace(' ', '', $median->plaintext));
        }
            
    // ****** END SCRAPE $tcgPlayerSetURL ******

    // ****** INSERT INTO TABLE ******

    // ****** END INSERT INTO TABLE ******


?>