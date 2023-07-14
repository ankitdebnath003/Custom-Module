<?php

namespace Drupal\plugin_example\Plugin\Block;

<<<<<<< HEAD
use Drupal\user\Entity\Role;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
=======
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Session\AccountInterface;
use Drupal\user\Entity\Role;
>>>>>>> FT2023-307
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Create a custom block to show the user a welcome message with their roles.
 *
 * @Block(
 *   id = "plugin_example_example",
 *   admin_label = @Translation("Example"),
 *   category = @Translation("Plugin Example")
 * )
 *
 * @package Drupal\plugin_example\Plugin\Block
 */
<<<<<<< HEAD
class ExampleBlock extends BlockBase implements ContainerFactoryPluginInterface
{  
=======
class ExampleBlock extends BlockBase implements ContainerFactoryPluginInterface {

>>>>>>> FT2023-307
  /**
   * This variable is used to store the current user's information.
   *
   * @var object
   */
  protected $currentUser;
<<<<<<< HEAD
    
=======

>>>>>>> FT2023-307
  /**
   * This variable is used to store the EntityTypeManager object.
   *
   * @var object
   */
  protected $entityTypeManager;

  /**
   * This variable is used to store the CurrentRouteMatch object.
   *
   * @var object
   */
  protected $route;

  /**
<<<<<<< HEAD
   * This constructor is used to set the current user's account information and 
   * entity type manager to the class variable and call the parent constructor 
   * with other values to set.
=======
   * This constructor is initilizing the instances.
>>>>>>> FT2023-307
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
<<<<<<< HEAD
   * @param \Drupal\Core\Session\AccountInterface $currentUser
   *   Stores the information of the current user.
   * @param \Drupal\Core\Entity\EntityTypeManager $entityTypeManager
=======
   * @param \Drupal\Core\Session\AccountInterface $account
   *   Stores the information of the current user.
   * @param \Drupal\Core\Entity\EntityTypeManager $entity
>>>>>>> FT2023-307
   *   Stores the object of the EntityTypeManager.
   * @param \Drupal\Core\Routing\CurrentRouteMatch $route
   *   Stores the object of the CurrentRouteMatch.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, AccountInterface $account, EntityTypeManager $entity, CurrentRouteMatch $route) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentUser = $account;
    $this->entityTypeManager = $entity;
    $this->route = $route;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user'),
      $container->get('entity_type.manager'),
      $container->get('current_route_match'),
    );
  }

  /**
   * This function is used to get all the role names of the user.
   *
   * @return array
   *   The display names of the roles of the user.
   */
  private function getUserRoles() {
    $roles = $this->currentUser->getRoles();
    $roles = array_diff($roles, ['authenticated']);
    $role_storage = $this->entityTypeManager->getStorage('user_role');

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
    $route_name = $this->route->getRouteName();
    if ($route_name == 'plugin_example.example') {
      return AccessResult::allowed();
    }
    return AccessResult::forbidden();
  }

}
