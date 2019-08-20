<?php

function findSellPrice($inputPrice) {
    $sellPrice = number_format(($inputPrice * 0.95),2);
    return $sellPrice;
    
}

function findBuyPrice($inputPrice) {
    //=if(isblank(D3),,if(or(D3="—",D3="Market Price"),"—",if(or(B3="C",B3="U",B3="T",B3="L",B3="P"),if(D3>0.99,mround(D3*0.35,0.1),if(D3>0.49,mround(D3*0.1,0.05),if(D3="U",0.01,0)))
    // ,if(and(or(B3="R",B3="M"),D3<2),if(B3="R",0.08,0.25),if(D3<3,mround(0.2*D3,0.05),if(D3<10,mround(0.4*D3,0.1),if(D3<25,mround(0.45*D3,0.25),if(D3<50,mround(0.5*D3,0.25),if(D3<100,mround(0.55*D3,0.5),mround(0.6*D3,0.5))))))))))
    if ($inputPrice >= 0.51 && $inputPrice <= 1.99)
    {
      $buyPrice = number_format(0.10,2);
    }
    
    if ($inputPrice >= 2.00 && $inputPrice <= 2.99)
    {
        $buyPrice = number_format(0.25,2);
    }
    
    if ($inputPrice <= 0.50)
    {
        $buyPrice = number_format(0.00,2);
    }
    if ($inputPrice > 3)
    {
        $buyPrice = number_format(($inputPrice * 0.47),2);
    }
    if($inputPrice >= 100)
    {
        $buyPrice = number_format(($inputPrice * 0.6),2);
    }
    return $buyPrice;

}
?>