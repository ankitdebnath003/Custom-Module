<?php

namespace Drupal\custom_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * This field formatter is showing the colors as a text and then the color code.
 *
 * @FieldFormatter(
 *   id = "color_code",
 *   label = @Translation("Color Code Formatter"),
 *   field_types = {
 *     "rgb"
 *   }
 * )
 */
class ColorCodeFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $color = $items[0]->color_combination;

    // Checking if the color code is in hex format or not.
    if (substr($color, 0, 1) === '#') {
      return [
        '#markup' => $this->t('Hex Code : @hex', [
          '@hex' => $color,
        ]),
      ];
    }

    // Converting the color code to array of RGB.
    $color = json_decode($color, TRUE);
    $elements[] = [
      '#markup' => $this->t('Red: @red, Green: @green, Blue: @blue', [
        '@red' => $color['red'],
        '@green' => $color['green'],
        '@blue' => $color['blue'],
      ]),
    ];

    return $elements;
  }

}
