<?php

namespace Drupal\flagship_form\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Create a block to show the Flagship Form data.
 *
 * @Block(
 *   id = "flagship_data",
 *   admin_label = @Translation("Flagship Data"),
 *   category = @Translation("Flagship")
 * )
 */
<<<<<<< HEAD
class FlagshipFormData extends BlockBase implements ContainerFactoryPluginInterface
{
=======
class FlagshipFormData extends BlockBase {

>>>>>>> FT2023-307
  /**
   * This variable is used to store the ConfigFactoryInterface object.
   *
   * @var object
   */
  protected $config;

  /**
   * This constructor is used to set the Config Factory Interface to the 
   * class variable and call the parent constructor with other values to set.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config
   *   Stores the object of ConfigFactoryInterface.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->config = $config;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $flagship = $this->config->get('flagship_form.settings')->get('data');
    return [
      '#theme' => 'flagship_form',
      '#title' => 'Flagship Form',
      '#data' => $flagship,
      '#attached' => [
        'library' => [
          'flagship_form/flagship-form',
        ],
      ],
    ];
  }

}
