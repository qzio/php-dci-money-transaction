#!/usr/bin/env php
<?php
echo "starting\n";
require_once __DIR__.'/bootstrap.php';

function show_roles($object)
{
  $roles = array_map(function($o){return $o->name();}, $object->roles());
  echo "{$object->identity} plays roles: ".implode(", ", $roles)."\n";
}




$transferAmount = 200;
$a1= new StdObj('account123', array('currentAmount' => 10000));
$a2 = new StdObj('account456', array('currentAmount' => 200));

echo "initiate conext MoneyTransfer on amount {$transferAmount}\n";
echo "from {$a1->identity} => {$a2->identity}\n";

echo "before:\n";
echo "{$a1->identity} has {$a1->currentAmount} \n";
echo "{$a2->identity} has {$a2->currentAmount} \n";


$transferMoney = new MoneyTransfer($a1, $a2, $transferAmount);

show_roles($a1);
show_roles($a2);

$transferMoney->execute();
//$transferMoney->execute(); // it is possible to execute twice.. good or bad? setting?
$transferMoney->teardown(); // @todo should be nicer if __destruct could be used

echo "after:\n";
echo "{$a1->identity} has {$a1->currentAmount} \n";
echo "{$a2->identity} has {$a2->currentAmount} \n";
show_roles($a1);
show_roles($a2);

echo "\n";
