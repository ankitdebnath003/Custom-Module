<?php

namespace Drupal\flagship_form\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * This controller is created to return to a page that renders the flagship form
 * block.
 */
class FlagshipFormController extends ControllerBase {

  /**
   * Return to the page that renders the Flagship Form Block.
   */
  public function showForm() {
    return [
      '#title' => 'Flagship Form',
    ];
  }

}
