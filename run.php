#!/usr/bin/env php
<?php
echo "starting\n";
require_once __DIR__.'/bootstrap.php';




$transferAmount = 200;
$a1= new StdObj('account123', array('currentAmount' => 10000));
$a2 = new StdObj('account456', array('currentAmount' => 200));

echo "initiate conext MoneyTransfer on amount {$transferAmount}\n";
echo "from {$a1->identity} => {$a2->identity}\n";
echo "before:\n";
echo "{$a1->identity} has {$a1->currentAmount} \n";
echo "{$a2->identity} has {$a2->currentAmount} \n";
$transfer_money = new MoneyTransfer($a1, $a2, $transferAmount);

echo "after:\n";
echo "{$a1->identity} has {$a1->currentAmount} \n";
echo "{$a2->identity} has {$a2->currentAmount} \n";
echo "done\n";

