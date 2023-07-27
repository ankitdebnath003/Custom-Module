<?php

namespace Drupal\custom_field\Plugin\Field\FieldWidget;

use Drupal\Component\Utility\Color;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * This field widget is the base widget.
 *
 * This is used to define some functions that will be used in multiple widgets.
 * Checks if the user is admin or not and convert the value to
 * corresponding rgb or hex based on the type.
 */
class ColorWidgetBase extends WidgetBase {

  /**
   * Stores boolean value to check if the role has access to view the fields.
   *
   * @var bool
   */
  protected $access = TRUE;

  /**
   * Constructs a ColorWidgetBase object.
   *
   * @param string $plugin_id
   *   The plugin_id for the widget.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the widget is associated.
   * @param array $settings
   *   The widget settings.
   * @param array $third_party_settings
   *   Any third party settings.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   Stores the object of Account Proxy Interface Class.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, array $third_party_settings, AccountProxyInterface $current_user) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);
    $this->checkAccess($current_user);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['third_party_settings'],
      $container->get('current_user'),
    );
  }

  /**
   * This function is used to check if the user is admin or not.
   *
   * If the user is not an admin then set the class $access variable to FALSE so
   * that the user can't see the fields.
   *
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   Stores the object of Account Proxy Interface class.
   */
  public function checkAccess(AccountProxyInterface $current_user) {
    $role = $current_user->getRoles();

    if (!in_array('administrator', $role)) {
      $this->access = FALSE;
    }
  }

  /**
   * This function is used to convert the color values according to the widgets.
   *
   * @param \Drupal\Core\Field\FieldDefinitionInterface $items
   *   Stores all the metadata of the fields.
   * @param int $delta
   *   Stores the integer value.
   * @param string $type
   *   Stores the type in which the value will be converted.
   *
   * @return string|array
   *   If the widget is set to rgb then returns the array otherwise hex string.
   */
  public function convertColor(FieldItemListInterface $items, $delta, string $type) {
    $color = $items[$delta]->color_combination ?? '';

    // Checking if the type is of hex and also if the colors are stored in the
    // hex format or not.
    if ($type === 'hex' && $color && substr($color, 0, 1) !== '#') {
      $color = json_decode($color, TRUE);
      return Color::rgbToHex($color);
    }

    // Checking if the type is of rgb and also if the colors are stored in the
    // rgb format or not.
    elseif ($type === 'rgb') {
      if ($color && substr($color, 0, 1) === '#') {
        return Color::hexToRgb($color);
      }
      return json_decode($color, TRUE);
    }

    // If the type is of hex and the values are stored in hex format also then
    // return the hex value as it is.
    return $color;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, Array $element, Array &$form, FormStateInterface $form_state) {
  }

}
