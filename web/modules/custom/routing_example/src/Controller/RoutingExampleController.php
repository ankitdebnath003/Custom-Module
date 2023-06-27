<?php

namespace Drupal\routing_example\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\user\Entity\User;

/**
 * This class is used to show how routing is used, how to use custom access
 * checks, and how to take value from dynamic parameters.
 * 
 * @package Drupal\routing_example\Controller
 * 
 * @author Ankit Debnath <ankit.debnath@innoraft.com>
 */
class RoutingExampleController extends ControllerBase 
{  

  /**
   * This variable is used to store the user's information fetching from User Entity.
   *
   * @var object
   */
  private $userEntity;
  
  /**
   * This constructor is used to load the CurrentUser service of drupal and by 
   * using it we can fetch the user's full information from the user entity.
   *
   * @return void
   */
  public function __construct() {
    $user = \Drupal::currentUser();
    $this->userEntity = User::load($user->id());
  }

  /**
   * This function is used to show the current user a hello message with their name.
   *
   * @return array
   *   Returns the hello message with the current user's name.
   */
  public function example() {
    return [
      '#title' => 'Hello ' . ucfirst($this->userEntity->getAccountName())
    ];
  }
}

?>
