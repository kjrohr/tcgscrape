<?php
$list = array
(
"Peter,Griffin,Oslo,Norway",
"Glenn,Quagmire,Oslo,Norway",
);

$file = fopen("output/contacts.csv","w");

fputcsv($file,array('Product Name','Category Name','Sell Price','Buy Price'));

foreach ($list as $line)
  {
  fputcsv($file,explode(',',$line));
  }

fclose($file); 

?>