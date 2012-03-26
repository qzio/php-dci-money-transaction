<?php
class MoneyTransfer extends Context
{
  protected $fromAccount = null;
  protected $toAccount = null;

  /**
   * setup + initiate execution of the use case
   */
  public function __construct($fromAccount, $toAccount, $transferAmount)
  {
    if ($transferAmount < 0)
    {
      throw new DCI_Exception('cant transfer negative founds');
    }
    $this->delegateRole('fromAccount', $fromAccount);
    $this->delegateRole('toAccount', $toAccount);
    $this->executeTransaction($transferAmount);
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
    if ( ! isset($this->player->currentAmount) ) {
      throw new DCI_Exception('the player doesnt have the currentAmount property, cant play this role');
    }
  }

  public function transferProperties()
  {
    $this->currentAmount = $this->player->currentAmount;
  }
}

/**
 * the account which will be credited
 */
class Role_FromAccount extends Role_Account
{
  public function credit($amount)
  {
    $this->currentAmount = ($this->currentAmount - $amount);
    if ($this->currentAmount < 0)
    {
      throw new DCI_Exception('unable go credit on the from account');
    }
    $this->updateProperty('currentAmount');
  }
}

/**
 * the accounnt which will have more money
 */
class Role_ToAccount extends Role_Account
{
  public function deposit($amount)
  {
    $this->currentAmount = $this->currentAmount + $amount;
    $this->updateProperty('currentAmount');
  }
}
