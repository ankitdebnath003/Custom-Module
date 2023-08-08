<?php

namespace Drupal\twig\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Twig routes.
 */
class TwigController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {
    return [
      '#title' => 'Webform & Paragraph',
    ];
  }

}
