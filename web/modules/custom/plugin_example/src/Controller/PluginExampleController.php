<?php

namespace Drupal\plugin_example\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * It is used to show the block in the particular route.
 *
 * @package Drupal\plugin_example\Controller
 */
class PluginExampleController extends ControllerBase {

  /**
   * This function is used to show a message to the user with the custom block.
   *
   * @return array
   *   The message to display on the page.
   */
  public function customPage() {
    return [
      '#title' => 'Welcome to Plugin Example',
    ];
  }

}
