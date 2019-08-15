<?php 
    include_once 'simplehtmldom_1_9/simple_html_dom.php';

    $urlList = array("https://shop.tcgplayer.com/price-guide/magic/core-set-2020", 
    //"https://shop.tcgplayer.com/price-guide/magic/modern-horizons",
    //"https://shop.tcgplayer.com/price-guide/magic/war-of-the-spark",
    //"https://shop.tcgplayer.com/price-guide/magic/ravnica-allegiance",
    //"https://shop.tcgplayer.com/price-guide/magic/ultimate-masters"
);

$setName = "";
$cardNames = array();
$medianPrices = array();
$sellPrice = array();
$buyPrice = array();

// if the table is set destroy and create
try{
  $pdo = new PDO("mysql:host=localhost;dbname=mtg", "root", "root");
  // Set the PDO error mode to exception
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
  die("ERROR: Could not connect. " . $e->getMessage());
}

// Attempt insert query execution
try{
  $cardId = $n + 1;
  echo $cardId . "<br />";
  // Create prepared statement
  $sql = "DROP TABLE IF EXISTS coreSet2020";
  
  $stmt = $pdo->prepare($sql);

  // Execute the prepared statement
  $stmt->execute();
} catch(PDOException $e){
  die("ERROR: Could not able to execute $sql. " . $e->getMessage());
}

// Close connection
unset($pdo);


try{
  echo "last try hit";
  $pdo = new PDO("mysql:host=localhost;dbname=mtg", "root", "root");
  // Set the PDO error mode to exception
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
  die("ERROR: Could not connect. " . $e->getMessage());
}

// Attempt insert query execution
try{
  $cardId = $n + 1;
  echo $cardId . "<br />";
  // Create prepared statement
  $sql = "create table coreSet2020(
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

for ($i = 0; $i < count($urlList); $i++)
{
    $html = file_get_html($urlList[$i]);

    $data = $html->find('.productDetail a');
    $price = $html->find('.medianPrice .cellWrapper');

    
    foreach ($data as $card)
        array_push($cardNames, str_replace("&#39;", "'", $card->plaintext));
        

    foreach ($price as $median)
        array_push($medianPrices, str_replace(' ', '', $median->plaintext));
        
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=
    
    , initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <td>Number</td>
                <td>Card Name</td>
                <td>Median Price</td>
            </tr>
        </thead>
        <tbody>
            <?php 
                for($x = 0; $x < count($cardNames); $x++)
                {
                    echo "<tr><td>" . $x . "</td><td>" . $cardNames[$x] . "</td><td>" . $medianPrices[$x] . "</td></tr>";

                    /* Attempt MySQL server connection. Assuming you are running MySQL
                    server with default setting (user 'root' with no password) */
                    try{
                        $pdo = new PDO("mysql:host=localhost;dbname=mtg", "root", "root");
                        // Set the PDO error mode to exception
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    } catch(PDOException $e){
                        die("ERROR: Could not connect. " . $e->getMessage());
                    }
                    
                    // Attempt insert query execution
                    try{
                        // Create prepared statement
                        $sql = "INSERT INTO coreSet2020 (cardName, medianPrice) VALUES (:cardName, :medianPrice)";
                        $stmt = $pdo->prepare($sql);
                        
                        // Bind parameters to statement
                        $stmt->bindParam(':cardName', $cardNames[$x]);
                        $stmt->bindParam(':medianPrice', $medianPrices[$x]);

                        
                        // Execute the prepared statement
                        $stmt->execute();
                        
                    } catch(PDOException $e){
                        die("ERROR: Could not able to execute $sql. " . $e->getMessage());
                    }
                    
                    // Close connection
                    unset($pdo);
                }
            ?>
        </tbody>
    </table>
</body>
</html>
<?php
try{
    $pdo = new PDO("mysql:host=localhost;dbname=mtg", "root", "root");
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Attempt insert query execution
try{
    // Create prepared statement
    $sql = "UPDATE coreSet2020 SET medianPrice = '$0.00' where medianPrice = '&mdash;'";
    
    $stmt = $pdo->prepare($sql);
    
    // Bind parameters to statement
    $stmt->bindParam(':cardName', $cardNames[$x]);
    $stmt->bindParam(':medianPrice', $medianPrices[$x]);

    
    // Execute the prepared statement
    $stmt->execute();
    
} catch(PDOException $e){
    die("ERROR: Could not able to execute $sql. " . $e->getMessage());
}

// Close connection
unset($pdo);
//&mdash
?>

<?php

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

$stmt = $pdo->query('SELECT * FROM coreSet2020');
while ($row = $stmt->fetch()){
    $rowValue = $row['medianPrice'];
    $rowValue = str_replace('$', '', $rowValue);
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
    if ($theSellPrice > 3){
      $theBuyPrice = number_format(($theSellPrice * 0.47),2);
    }
    
    array_push($sellPrice, $theSellPrice);
    array_push($buyPrice, $theBuyPrice);
    //echo "Old: " . $numValue . ' | New: ' . $theSellPrice . "<br />";

}

echo "<br /> COUNT <br />";
echo count($sellPrice);
echo "<br /> END COUNT <br />";
echo "<br /> LOOP <br />";
for ($x = 0; $x < count($sellPrice); $x++){
  echo $sellPrice[$x] . "<br />";
}

echo "<br /> END LOOP <br />";

?>

<?php
for($n = 0; $n < count($sellPrice); $n++){
  try{
    echo "last try hit";
    $pdo = new PDO("mysql:host=localhost;dbname=mtg", "root", "root");
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Attempt insert query execution
try{
    $cardId = $n + 1;
    echo $cardId . "<br />";
    // Create prepared statement
    $sql = "UPDATE coreSet2020 SET sellPrice = :sellPrice, buyPrice = :buyPrice where cardId = :cardId";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':sellPrice', $sellPrice[$n]);
    $stmt->bindParam(':buyPrice', $buyPrice[$n]);
    $stmt->bindParam(':cardId', $cardId);

    // Execute the prepared statement
    $stmt->execute();
} catch(PDOException $e){
    die("ERROR: Could not able to execute $sql. " . $e->getMessage());
}

// Close connection
unset($pdo);
//&mdash
}

?>