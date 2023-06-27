<?php

namespace Drupal\routing_example\Controller;

use Drupal\Core\Controller\ControllerBase;

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
   * This function is used to show the current user a hello message with their name.
   *
   * @return array
   *   Returns the hello message with the current user's name.
   */
  public function example() {
    $user_name = \Drupal::currentUser()->getAccountName();
    return [
      '#title' => 'Hello ' . ucfirst($user_name)
    ];
  }
}

?>
