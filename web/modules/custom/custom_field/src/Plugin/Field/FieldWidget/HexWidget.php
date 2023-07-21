<?php

namespace Drupal\custom_field\Plugin\Field\FieldWidget;

use Drupal\Component\Utility\Color;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * This field widget is taking input of hex color and store it in the database.
 *
 * @FieldWidget(
 *   id = "hex_widget",
 *   label = @Translation("Hex Color"),
 *   field_types = {
 *     "rgb"
 *   }
 * )
 */
class HexWidget extends ColorWidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, Array $element, Array &$form, FormStateInterface $form_state) {
    $color = $this->convertColor($items, $delta, 'hex');

    $element['color_combination'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Hex Code'),
      '#default_value' => $color ?? '',
      '#access' => $this->access,
    ];
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as $delta => $value) {
      $color = $value['color_combination'];
      if ($value['color_combination'] === '') {
        $values[$delta]['color_combination'] = NULL;
      }
      elseif (!Color::validateHex($value['color_combination'])) {
        $form_state->setErrorByName('color_combination', 'Invalid hex value');
      }
      // Checking if the user add # in the hex value or not. If the user does
      // not add it then add it to the string.
      elseif (substr($color, 0, 1) !== '#') {
        $values[$delta]['color_combination'] = '#' . $color;
      }
    }
    return $values;
  }

}
