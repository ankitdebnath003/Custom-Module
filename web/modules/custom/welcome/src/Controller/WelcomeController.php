<?php

namespace Drupal\welcome\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * This class is used to show the user the hello message with their names.
 */
class WelcomeController extends ControllerBase 
{

  /**
   * This function is used to show the current user a hello message with their name.
   *
   * @return array
   *   Returns the hello message with the current user's name.
   */
  public function welcomeCurrentUser() {
    $userName = \Drupal::currentUser()->getAccountName();
    return [
      '#title' => 'Hello ' . ucfirst($userName)
    ];
  }
}


?>
