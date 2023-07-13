<?php

namespace Drupal\custom_field\Plugin\Field\FieldWidget;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * This field widget is taking input of the color from a color picker and then 
 * store the hex value in the database.
 *
 * @FieldWidget(
 *   id = "color_picker",
 *   label = @Translation("Color Picker"),
 *   field_types = {
 *     "rgb"
 *   }
 * )
 */
class ColorPickerWidget extends ColorWidgetBase 
{
  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, Array $element, Array &$form, FormStateInterface $form_state) {
    $color = $this->convertColor($items, $delta, 'hex');

    $element['color_combination'] = [
      '#type' => 'color',
      '#title' => $this->t('Pick Color'),
      '#default_value' => isset($color) ? $color : '',
      '#access' => $this->access,
    ];
    return $element;
  }
}
