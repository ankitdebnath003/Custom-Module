<?php

namespace Drupal\custom_field\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Creates a custom field type for taking input of color.
 *
 * @FieldType(
 *   id = "rgb",
 *   label = @Translation("Color"),
 *   category = @Translation("Color"),
 *   default_widget = "rgb_widget",
 *   default_formatter = "color_code"
 * )
 */
class CustomFieldType extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'color_combination' => [
          'type' => 'varchar',
          'length' => 255,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $color = $this->get('color_combination')->getValue();
    return empty($color);
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['color_combination'] = DataDefinition::create('string')
      ->setLabel('Color Combination');

    return $properties;
  }

}
