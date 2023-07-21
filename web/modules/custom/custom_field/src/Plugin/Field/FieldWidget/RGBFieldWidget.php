<?php

namespace Drupal\custom_field\Plugin\Field\FieldWidget;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * This field widget is taking input of RGB color and store it in the database.
 *
 * @FieldWidget(
 *   id = "rgb_widget",
 *   label = @Translation("RGB Color"),
 *   field_types = {
 *     "rgb"
 *   }
 * )
 */
class RGBFieldWidget extends ColorWidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, Array $element, Array &$form, FormStateInterface $form_state) {
    $colors = $this->convertColor($items, $delta, 'rgb') ?? [];
    $element['color_combination'] = [
      '#access' => $this->access,
      '#attributes' => [
        'class' => [
          'rgb-color-wrapper',
        ],
      ],
    ];
    $element['color_combination']['red'] = [
      '#type' => 'number',
      '#title' => $this->t('Red'),
      '#size' => 15,
      '#min' => 0,
      '#max' => 255,
      '#default_value' => $colors['red'] ?? '',
    ];

    $element['color_combination']['green'] = [
      '#type' => 'number',
      '#title' => $this->t('Green'),
      '#size' => 15,
      '#min' => 0,
      '#max' => 255,
      '#default_value' => $colors['green'] ?? '',
    ];

    $element['color_combination']['blue'] = [
      '#type' => 'number',
      '#title' => $this->t('Blue'),
      '#size' => 15,
      '#min' => 0,
      '#max' => 255,
      '#default_value' => $colors['blue'] ?? '',
    ];

    $element['#theme_wrappers'] = ['container', 'form_element'];
    $element['#attributes']['class'][] = 'container-inline';
    $element['#attributes']['class'][] = 'custom-rgb-field-elements';
    $element['#attached']['library'][] = 'custom_field/custom-field-css';

    return $element;
  }

  /**
   * This function is used to validate the RGB color.
   *
   * @param array $colors
   *   Stores the red, green and blue colors.
   *
   * @return string|bool
   *   Returns invalid color name if any otherwise TRUE.
   */
  public function validateColor(array $colors) {
    if (!($colors[0] >= 0 && $colors[0] <= 255)) {
      return 'red';
    }
    elseif (!($colors[1] >= 0 && $colors[1] <= 255)) {
      return 'green';
    }
    elseif (!($colors[2] >= 0 && $colors[2] <= 255)) {
      return 'blue';
    }
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as $delta => $value) {
      $rgb = [
        $value['color_combination']['red'],
        $value['color_combination']['green'],
        $value['color_combination']['blue'],
      ];
      $color = $this->validateColor($rgb);
      if ($value['color_combination']['red'] === '' && $value['color_combination']['green'] === '' && $value['color_combination']['blue'] === '') {
        $values[$delta]['color_combination'] = NULL;
      }
      elseif ($color !== TRUE) {
        $form_state->setErrorByName($this->fieldDefinition->getName(), $this->t('@color color is invalid', ['@color' => $color]));
      }
      else {
        $values[$delta]['color_combination'] = Json::encode($value['color_combination']);
      }
    }
    return $values;
  }

}
