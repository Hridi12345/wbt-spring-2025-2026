<?php
$length = 10;
$width = 5;
$area = $length * $width;
$perimeter = 2 * ($length + $width);

echo "Area of rectangle: $area";
echo "  Perimeter: $perimeter <br>";

$amount = 2000;
$vat = 0.15 * $amount;
echo "Amount is $amount and vat is $vat <br>";

$number = 12;
if ($number % 2 === 0){
    echo " Number $number is even <br>";
}
else{
    echo "Number $number is odd <br>";
}


$num1=10;
$num2=5;
$num3=7;
if ($num1 > $num2 && $num1 > $num3)
    {echo "$num1 is largest <br>";}
elseif ($num2 > $num1 && $num2 > $num3)
    {echo "$num2 is largest <br>";}
else{
    echo "$num3 is largest <br>";
}


for ($i = 10; $i <= 100; $i++) {
    if ($i % 2 != 0) {
        echo "$i  ";
    } else {
        
    }
}
echo "<br>";


$arr = [1, 9, 5, 10, 12, 22];
$n = 5;

for ($i = 0; $i <5; $i++) {
    if ($arr[$i] == $n) {
        echo "Found the element $n  <br>";
    }
}


for ($i = 1; $i <= 3; $i++) {
    for ($j = 1; $j <= $i; $j++) {
        echo "* ";
    }
    echo "<br>";
}

for ($i = 3; $i >= 1; $i--) {   
    for ($j = 1; $j <= $i; $j++) {  
        echo "$j ";
    }
    echo "<br>";
}

$char = 'A';
for($i=1; $i<=3; $i++){
    for($j=1; $j<=$i; $j++){
        echo "$char ";
        $char++;
    }
        echo "<br>";
}
?>