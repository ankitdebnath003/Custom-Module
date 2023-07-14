<?php

namespace Drupal\welcome\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * This class is used to show the user the hello message with their names.
 *
 * @package Drupal\welcome\Controller
 */
class WelcomeController extends ControllerBase {

  /**
   * Stores the metadata of Account Interface.
   *
   * @var Drupal\Core\Session\AccountInterface
   */
  private $account;

  /**
   * Initialize the object of AccountInterface to the class variable.
   *
   * @param Drupal\Core\Session\AccountInterface $account
   *   Stores the account information of the currently logged in user.
   */
  public function __construct(AccountInterface $account) {
    $this->account = $account;
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
   * This function is used to show the current user a hello message with name.
   *
   * @return array
   *   Returns the hello message with the current user's name.
   */
  public function welcomeCurrentUser() {
    $user_name = $this->account->getAccountName();
    return [
      '#title' => 'Hello ' . ucfirst($user_name),
    ];
  }

}
