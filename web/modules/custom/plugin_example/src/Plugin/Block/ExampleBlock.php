<?php

namespace Drupal\plugin_example\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\user\Entity\Role;
use Drupal\user\Entity\User;

/**
 * Create a custom block to show the user a welcome message with their roles and
 * specify on which route this block will be shown.
 *
 * @Block(
 *   id = "plugin_example_example",
 *   admin_label = @Translation("Example"),
 *   category = @Translation("Plugin Example")
 * )
 * 
 * @package Drupal\plugin_example\Plugin\Block
 * 
 * @author Ankit Debnath <ankit.debnath@innoraft.com>
 */
class ExampleBlock extends BlockBase 
{
  
  /**
   * This function is used to get all the role names of the user.
   *
   * @return array
   *   The display names of the roles of the user.
   */
  private function getUserRoles() {
    $user = \Drupal::currentUser();
    $user_entity = User::load($user->id());

    $roles = $user_entity->getRoles();
    $roles = array_diff($roles, ['authenticated']);
    $role_storage = \Drupal::entityTypeManager()->getStorage('user_role');

    // Getting the display names of the roles instead of machine names.
    foreach ($roles as $role) {
      $role_object = $role_storage->load($role);
      if ($role_object instanceof Role) {
        $role_names[] = $role_object->label();
      }
    }

    // Converting the array of the roles to string separated by comma.
    $role_names = implode(", ", $role_names);

    return $role_names;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $roles = $this->getUserRoles();
    $build['content'] = [
      '#markup' => '<h3>Welcome ' . $roles . '</h3>',
    ];
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    $route_name = \Drupal::routeMatch()->getRouteName();
    if ($route_name == 'plugin_example.example') {
      return AccessResult::allowed();
    }
    return AccessResult::forbidden();
  }
}

?>