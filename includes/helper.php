<?php

function findSellPrice() {

}

function findBuyPrice() {
    
}

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

?>