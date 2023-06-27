<?php

namespace Drupal\routing_example\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\user\Entity\User;
use Drupal\Core\Access\AccessResult;

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
   * This function is used to check custom access to a route. If a user has a 
   * specific permission then the user can view the page.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The result of accessing the page based on the permission and deny if the 
   *   user doesn't have the permission.
   */
  public function customAccessCheck() {
    // Checking if the role has the custom permission.
    if ($this->userEntity->hasPermission('access the custom page')) {
      return AccessResult::allowed();
    }
    return AccessResult::forbidden();
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

  /**
   * This function takes a dynamic value from url and display it.
   *
   * @param int $data
   *   This is the dynamic value getting from the url.
   * 
   * @return array
   *   An array with title showing the number of campaign page the user is 
   *   currently at.
   */
  public function campaign(int $data) {
    return [
      '#title' => 'You are in the ' . $data . ' page of the campaign.'
    ];
  }
}

?>
