<?php

namespace Drupal\flagship_form\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Create a block to show the Flagship Form data.
 *
 * @Block(
 *   id = "flagship_data",
 *   admin_label = @Translation("Flagship Data"),
 *   category = @Translation("Flagship")
 * )
 */
class FlagshipFormData extends BlockBase 
{
  /**
   * {@inheritdoc}
   */
  public function build() {
    $flagship = \Drupal::config('flagship_form.settings')->get('data');
    return [
      '#theme' => 'flagship_form',
      '#title' => 'Flagship Form',
      '#data' => $flagship,
      '#attached' => [
        'library' => [
          'flagship_form/flagship-form',
        ],
      ],
    ];
  }

}