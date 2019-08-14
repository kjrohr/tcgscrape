<?php 
    include_once 'simplehtmldom_1_9/simple_html_dom.php';

    $urlList = array("https://shop.tcgplayer.com/price-guide/magic/core-set-2020", 
    //"https://shop.tcgplayer.com/price-guide/magic/modern-horizons",
    //"https://shop.tcgplayer.com/price-guide/magic/war-of-the-spark",
    //"https://shop.tcgplayer.com/price-guide/magic/ravnica-allegiance",
    //"https://shop.tcgplayer.com/price-guide/magic/ultimate-masters"
);
$cardNames = array();
$medianPrices = array();
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
                        echo "Records inserted successfully.";
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
    echo "Records inserted successfully.";
} catch(PDOException $e){
    die("ERROR: Could not able to execute $sql. " . $e->getMessage());
}

// Close connection
unset($pdo);
//&mdash
?>