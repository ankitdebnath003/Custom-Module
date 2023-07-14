<?php

namespace Drupal\custom_field\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Color;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * This field formatter sets the colors as background colors of a text.
 *
 * @FieldFormatter(
 *   id = "background_color_code",
 *   label = @Translation("Background Color Formatter"),
 *   field_types = {
 *     "rgb"
 *   }
 * )
 */
class BackgroundColorFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $color = $items[0]->color_combination;

    // Checking if the color is in rgb then convert the rgb to its hex value.
    if (substr($color, 0, 1) !== '#') {
      $color = json_decode($color, TRUE);
      $color = Color::rgbToHex($color);
    }

    $build = [
      '#theme' => 'custom_field',
      '#dynamic_color' => $color,
      '#attached' => [
        'library' => [
          'custom_field/custom-field',
        ],
      ],
    ];
    return $build;
  }

}
