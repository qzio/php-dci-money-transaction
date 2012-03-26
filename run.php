#!/usr/bin/env php
<?php
echo "starting\n";
require_once __DIR__.'/bootstrap.php';




$transferAmount = 200;
$a1= new StdObj('account123', array('amount' => 10000));
$a2 = new StdObj('account456', array('amount' => 200));

echo "initiate conext MoneyTransfer on amount {$transferAmount}\n";
echo "from {$a1->identity} => {$a2->identity}\n";
echo "before:\n";
echo "{$a1->identity} has {$a1->amount} \n";
echo "{$a2->identity} has {$a2->amount} \n";
$transfer_money = new MoneyTransfer($a1, $a2, $transferAmount);

echo "after:\n";
echo "{$a1->identity} has {$a1->amount} \n";
echo "{$a2->identity} has {$a2->amount} \n";
echo "done\n";

