<?php

namespace Drupal\custom_field\Plugin\Field\FieldWidget;

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
    $color = $this->convertColor($items, $delta, 'rgb');

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
      '#default_value' => $color['red'] ?? '',
    ];

    $element['color_combination']['green'] = [
      '#type' => 'number',
      '#title' => $this->t('Green'),
      '#size' => 15,
      '#min' => 0,
      '#max' => 255,
      '#default_value' => $color['green'] ?? '',
    ];

    $element['color_combination']['blue'] = [
      '#type' => 'number',
      '#title' => $this->t('Blue'),
      '#size' => 15,
      '#min' => 0,
      '#max' => 255,
      '#default_value' => $color['blue'] ?? '',
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
   * @param array $color
   *   Stores the red, green and blue colors.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Stores the form state.
   */
  public function validateColor(array $color, FormStateInterface $form_state) {
    if (!($color['red'] >= 0 && $color['red'] <= 255)) {
      $form_state->setErrorByName('red', $this->t("Red Color must be in the range 0 to 255."));
    }
    elseif (!($color['green'] >= 0 && $color['green'] <= 255)) {
      $form_state->setErrorByName('green', $this->t("Green Color must be in the range 0 to 255."));
    }
    elseif (!($color['blue'] >= 0 && $color['blue'] <= 255)) {
      $form_state->setErrorByName('blue', $this->t("Blue Color must be in the range 0 to 255."));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    $color = $values[0]['color_combination'];
    $this->validateColor($color, $form_state);
    return json_encode($color);
  }

}
