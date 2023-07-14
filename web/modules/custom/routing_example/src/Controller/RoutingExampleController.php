<?php

namespace Drupal\routing_example\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * This class is used to for routing.
 *
 * How it is used, how to use custom access checks, and how to take value from
 * dynamic parameters.
 *
 * @package Drupal\routing_example\Controller
 */
class RoutingExampleController extends ControllerBase {

  /**
   * This variable is used to store the current user's information.
   *
   * @var object
   */
  protected $currentUser;
<<<<<<< HEAD
  
  /**
   * This constructor is used set the current user's account information to the 
   * class variable.
=======

  /**
   * Initializes the AccountProxyInterface to the class variable.
>>>>>>> FT2023-307
   *
   * @param Drupal\Core\Session\AccountProxyInterface $account
   *   Stores the information of the current user.
   */
  public function __construct(AccountProxyInterface $account) {
    $this->currentUser = $account;
<<<<<<< HEAD
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user')
    );
=======
>>>>>>> FT2023-307
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user')
    );
  }

  /**
   * This function is used to check custom access to a route.
   *
   * If a user has a specific permission then the user can view the page.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The result of accessing the page based on the permission and deny if the
   *   user doesn't have the permission.
   */
  public function customAccessCheck() {
    // Checking if the role has the custom permission.
    if ($this->currentUser->hasPermission('access the custom page')) {
      return AccessResult::allowed();
    }
    return AccessResult::forbidden();
  }

  /**
   * This function is used to show the current user a hello message with name.
   *
   * @return array
   *   Returns the hello message with the current user's name.
   */
  public function example() {
    return [
<<<<<<< HEAD
      '#title' => 'Hello ' . ucfirst($this->currentUser->getAccountName())
=======
      '#title' => 'Hello ' . ucfirst($this->currentUser->getAccountName()),
>>>>>>> FT2023-307
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
      '#title' => 'You are in the ' . $data . ' page of the campaign.',
    ];
  }

}
