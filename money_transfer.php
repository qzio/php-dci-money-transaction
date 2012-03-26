<?php
class MoneyTransfer extends Context
{
  protected $fromAccount = null;
  protected $toAccount = null;
  protected $transferAmount = 0;

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
    $this->transferAmount = ($transferAmount);
  }

  public function execute()
  {
    $this->executeTransaction($this->transferAmount);
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
    return true;
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
  public $name = 'fromAccount';
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
  public $name = 'toAccount';
  public function deposit($amount)
  {
    $this->currentAmount = $this->currentAmount + $amount;
    $this->updateProperty('currentAmount');
  }
}
