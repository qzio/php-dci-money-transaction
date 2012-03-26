<?php
class MoneyTransfer extends Context
{
  protected $fromAccount = null;
  protected $toAccount = null;

  /**
   * setup + initiate execution of the use case
   */
  public function __construct($fromAccount, $toAccount, $amount)
  {
    if ($amount < 0)
    {
      throw new DCI_Exception('cant transfer negative founds');
    }
    $this->delegateRole('fromAccount', $fromAccount);
    $this->delegateRole('toAccount', $toAccount);
    $this->executeTransaction($amount);
  }

  /**
   * the use case.
   */
  protected function executeTransaction($amount)
  {
    try
    {
      $this->players['fromAccount']->credit($amount);
      $this->players['toAccount']->deposit($amount);
    }
    catch (DCI_Exception $e)
    {
      die('['.get_class().'] FAILED: '.$e->getMessage()."\n");
    }
    echo "\ntransaction between fromAccount({$this->players['fromAccount']->identity})".
     " and toAccount({$this->players['toAccount']->identity}) on amount({$amount})\n";
  }

}


/**
 * base account, stuff that are to be used in all account roles
 */
class Role_Account extends Role
{
  public $amount = 0;
  public function constraints()
  {
    if ( ! isset($this->player->amount) ) {
      throw new DCI_Exception('the player doesnt have the amount property, cant play this role');
    }
  }

  public function transferProperties()
  {
    $this->amount = $this->player->amount;
  }
}

/**
 * the account which will be credited
 */
class Role_FromAccount extends Role_Account
{
  public function credit($amount)
  {
    $this->amount = ($this->amount - $amount);
    if ($this->amount < 0)
    {
      throw new DCI_Exception('unable go credit on the from account');
    }
    $this->updateProperty('amount');
  }
}

/**
 * the accounnt which will have more money
 */
class Role_ToAccount extends Role_Account
{
  public function deposit($amount)
  {
    $this->amount = $this->amount + $amount;
    $this->updateProperty('amount');
  }
}
