<?php

namespace Drupal\flagship_form\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Create a block with a form for taking input.
 *
 * @Block(
 *   id = "flagship_form",
 *   admin_label = @Translation("Flagship Form"),
 *   category = @Translation("Flagship")
 * )
 */
class FlagshipFormBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('Drupal\flagship_form\Form\FlagshipForm');
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    $route_name = \Drupal::routeMatch()->getRouteName();
    if ($route_name == 'flagship_form.show_form') {
      return AccessResult::allowed();
    }
    return AccessResult::forbidden();
  }

}
