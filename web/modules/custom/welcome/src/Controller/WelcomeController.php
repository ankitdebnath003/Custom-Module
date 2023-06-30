<?php

namespace Drupal\welcome\Controller;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Controller\ControllerBase;

/**
 * This class is used to show the user the hello message with their names.
 * 
 * @package Drupal\welcome\Controller
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
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    $user_name = \Drupal::currentUser()->getAccountName();
    return [
      '#title' => 'Hello ' . ucfirst($user_name),
      '#cache' => [
        'tags' => $user->getCacheTags(),
      ]
    ];
  }
}


?>
