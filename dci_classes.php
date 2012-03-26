<?php
class DCI_Exception extends Exception
{
}

class Context
{
  protected $ctx = null;
  public function ctx($ctx = null)
  {
    $this->ctx = $ctx ?: $ctx;
    return $this->ctx;
  }

  public function delegateRole($role, $object)
  {
    $roleClass = 'Role_'.$role;
    $object->context($this);
    $object->injectRole(new $roleClass($object));
    $this->players[$role] = $object;
  }

  /**
   * remove the roles from the player objects
   */
  public function teardown()
  {
    foreach($this->players as $role => $object)
    {
      $object->withdrawRole($role);
    }
  }

}
class StdObj extends StdClass
{
  protected $roles = array();
  protected $context = null;

  public function __construct($identity = 'unknown', $params = array())
  {
    foreach($params as $key => $value)
    {
      $this->$key = $value;
    }
    $this->identity = $identity;
  }

  public function context($context = null)
  {
    $this->context = $context?:$context;
    return $this->context;
  }

  public function roles()
  {
    return $this->roles;
  }

  public function injectRole(Role $role)
  {
    $this->roles[$role->name()] = $role;
  }

  public function withdrawRole($roleName)
  {
    $this->roles[$roleName] = null;
    unset($this->roles[$roleName]);
  }

  public function __call($method, $args)
  {
    foreach($this->roles as $key => $role) {
      if (is_callable(array($role, $method))){
        return call_user_func_array(array($this->roles[$key], $method), $args);
      }
    }
    throw new DCI_Exception($this->identity.' doesnt have a role with the method '.$method);
  }

}

class Role
{
  protected $player = null;
  public    $name = 'role';

  public function __construct(StdObj $player)
  {
    $this->player = $player;
    $this->checkConstraints();
    $this->transferProperties();
  }

  public function name()
  {
    return $this->name;
  }
  /**
   * might throw exception
   */
  public function checkConstraints()
  {
  }

  /**
   * transfer properties from the player to the role...
   * @todo not good
   */
  public function transferProperties()
  {
  }

  public function updateProperty($prop)
  {
    $this->player->$prop = $this->$prop;
  }
}

