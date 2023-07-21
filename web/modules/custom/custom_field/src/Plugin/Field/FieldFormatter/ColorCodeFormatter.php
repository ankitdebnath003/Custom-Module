<?php

namespace Drupal\custom_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * This field formatter is showing the colors as a text of the color code.
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
    foreach ($items as $item) {
      $color = $item->color_combination;

      // Checking if the color is in Hex.
      if (substr($color, 0, 1) === '#') {
        $elements[] = [
          '#markup' => $this->t('Hex Code : @hex', [
            '@hex' => $color,
          ]),
        ];
      }
      else {
        // Converting the color code to array of RGB.
        $color = json_decode($color, TRUE);

        $elements[] = [
          '#markup' => $this->t('Red: @red, Green: @green, Blue: @blue', [
            '@red' => $color['red'],
            '@green' => $color['green'],
            '@blue' => $color['blue'],
          ]),
        ];
      }
    }
    return $elements;
  }

}
