<?php

namespace Drupal\custom_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * This field widget is taking input of the colo from a color picker.
 *
 * @FieldWidget(
 *   id = "color_picker",
 *   label = @Translation("Color Picker"),
 *   field_types = {
 *     "rgb"
 *   }
 * )
 */
class ColorPickerWidget extends ColorWidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, Array $element, Array &$form, FormStateInterface $form_state) {
    $color = $this->convertColor($items, $delta, 'hex');

    $element['color_combination'] = [
      '#type' => 'color',
      '#title' => $this->t('Pick Color'),
      '#default_value' => $color ?? '',
      '#access' => $this->access,
    ];
    return $element;
  }

}
