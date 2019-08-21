<?php
include_once 'simplehtmldom_1_9/simple_html_dom.php';

function findSellPrice($inputPrice) {
    $medianPrice = $inputPrice;
    $medianPrice = str_replace(",","", $medianPrice);
    $sellPrice = number_format($medianPrice * 0.95,2);
    return $sellPrice;
    
}

function findBuyPrice($inputPrice) {
    //=if(isblank(D3),,if(or(D3="—",D3="Market Price"),"—",if(or(B3="C",B3="U",B3="T",B3="L",B3="P"),if(D3>0.99,mround(D3*0.35,0.1),if(D3>0.49,mround(D3*0.1,0.05),if(D3="U",0.01,0)))
    // ,if(and(or(B3="R",B3="M"),D3<2),if(B3="R",0.08,0.25),if(D3<3,mround(0.2*D3,0.05),if(D3<10,mround(0.4*D3,0.1),if(D3<25,mround(0.45*D3,0.25),if(D3<50,mround(0.5*D3,0.25),if(D3<100,mround(0.55*D3,0.5),mround(0.6*D3,0.5))))))))))
    $sellPrice = $inputPrice;
    $sellPrice = str_replace(",","", $sellPrice);
    if ($sellPrice >= 0.51 && $sellPrice <= 1.99)
    {
      $buyPrice = number_format(0.10,2);
    }
    
    if ($sellPrice >= 2.00 && $sellPrice <= 2.99)
    {
        $buyPrice = number_format(0.25,2);
    }
    
    if ($sellPrice <= 0.50)
    {
        $buyPrice = number_format(0.00,2);
    }

    if ($sellPrice > 3)
    {
        $buyPrice = number_format(($sellPrice * 0.47),2);
    }

    if($sellPrice >= 100.00)
    {
        $buyPrice = number_format(($sellPrice * 0.6),2);
    }
    return $buyPrice;

}

function scrapeFoils($url){
    $returnArray = array();
    $html = file_get_html($url);
    $data = $html->find('tbody tr');
    $cardNamesArray = array();
    $foilPriceArray = array();
    
    foreach ($data as $card){
        array_push($cardNamesArray, str_replace("&#39;", "'", $card->first_child()->plaintext . " - Foil"));
        array_push($foilPriceArray, str_replace("$", "", str_replace('&mdash;', '0.00', $card->last_child()->prev_sibling()->plaintext)));
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
?>