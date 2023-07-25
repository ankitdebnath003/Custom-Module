<?php

namespace Drupal\budget_form\Form;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Stores the movie budget from the config form.
 */
class MovieBudgetForm extends ConfigFormBase {

  /**
   * Stores the config form name.
   *
   * @var string
   */
  const CONFIG = 'movie_budget_form.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'movie_budget_form_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [MovieBudgetForm::CONFIG];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(MovieBudgetForm::CONFIG);
    $form['budget'] = [
      '#type' => 'number',
      '#title' => $this->t('Example'),
      '#default_value' => $config->get('budget'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    Cache::invalidateTags(['node_view']);
    $this->config(MovieBudgetForm::CONFIG)
      ->set('budget', $form_state->getValue('budget'))
      ->save();
  }

}
