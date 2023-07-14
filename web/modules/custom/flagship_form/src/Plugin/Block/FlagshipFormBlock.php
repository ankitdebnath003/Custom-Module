<?php

namespace Drupal\flagship_form\Plugin\Block;

use Drupal\Core\Access\AccessResult;
<<<<<<< HEAD
use Drupal\Core\Form\FormBuilder;
=======
use Drupal\Core\Block\BlockBase;
>>>>>>> FT2023-307
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Create a block with a form for taking input.
 *
 * @Block(
 *   id = "flagship_form",
 *   admin_label = @Translation("Flagship Form"),
 *   category = @Translation("Flagship")
 * )
 */
<<<<<<< HEAD
class FlagshipFormBlock extends BlockBase implements ContainerFactoryPluginInterface
{
=======
class FlagshipFormBlock extends BlockBase {

>>>>>>> FT2023-307
  /**
   * This variable is used to store the CurrentRouteMatch object.
   *
   * @var object
   */
  protected $route;

  /**
   * This variable is used to store the FormBuilder object.
   *
   * @var object
   */
  protected $formBuilder;

  /**
   * This constructor is used to set the current route and form builder to the 
   * class variable and call the parent constructor with other values to set.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Routing\CurrentRouteMatch $route
   *   Stores the object of CurrentRouteMatch.
   * @param \Drupal\Core\Form\FormBuilder $form_builder
   *   Stores the object of FormBuilder.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CurrentRouteMatch $route, FormBuilder $form_builder) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->route = $route;
    $this->formBuilder = $form_builder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_route_match'),
      $container->get('form_builder'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
<<<<<<< HEAD
    $form = $this->formBuilder->getForm('Drupal\flagship_form\Form\FlagshipForm'); 
=======
    $form = \Drupal::formBuilder()->getForm('Drupal\flagship_form\Form\FlagshipForm');
>>>>>>> FT2023-307
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    $route_name = $this->route->getRouteName();
    if ($route_name == 'flagship_form.show_form') {
      return AccessResult::allowed();
    }
    return AccessResult::forbidden();
  }

}
