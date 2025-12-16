<?php
require __DIR__ . '/vendor/autoload.php';

use App\Clothing;
use App\Electronic;

$c = new Clothing(1);
$c->setName('T-Shirt')->setSize('M')->setColor('Blue')->setMaterialFee(5)->addStocks(10);

$e = new Electronic(2);
$e->setName('Headphones')->setBrand('Acme')->setWarantyFee(12)->addStocks(5);

echo "Clothing: {$c->getName()} size={$c->getSize()} color={$c->getColor()} qty={$c->getQuantity()}\n";
echo "Electronic: {$e->getName()} brand={$e->getBrand()} warranty={$e->getWarantyFee()} qty={$e->getQuantity()}\n";
                  