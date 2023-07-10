<?php

namespace Drupal\flagship_form\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * This is a config form that is created to take input from admin only and store 
 * the data of the form in the flagship_form.settings.yml file.
 * 
 * @package Drupal\flagship_form\Form
 * 
 * @author Ankit Debnath <ankit.debnath@innoraft.com>
 */
class FlagshipForm extends ConfigFormBase 
{
  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['flagship_form.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'flagship_form_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('flagship_form.settings');

    $form_data = $form_state->get('flagship_form_values');

    // Checking if the form data is empty then get the data from the config table.
    if (empty($form_data)) {
      $form_data = $config->get('data');
      $form_state->set('flagship_form_values', $form_data);
    }

    // Checking if the form data in the config table is also empty then create 
    // an array with empty values.
    // This is basically used to set the value for the first time after creating
    // the form as the config table will be empty.
    if (empty($form_data)) {
      $this->addOneRow($form, $form_state);
    }

    $form_data = $form_state->get('flagship_form_values');

    $form['flagship'] = [
      '#type' => 'table',
      '#title' => 'Flagship Table',
      '#header' => ['Group Name', '1st Label', '1st Value', '2nd Label', '2nd Value'],
      '#prefix' => '<div id="flagship-form-wrapper">',
      '#suffix' => '</div>',
    ];

    foreach ($form_data as $i => $tag) {
      $form['flagship'][$i]['group_name'] = [
        '#type' => 'textfield',
        '#size' => 15,
        '#default_value' => $tag['group_name'] ?? '',
      ];
      $form['flagship'][$i]['first_label'] = [
        '#type' => 'textfield',
        '#size' => 15,
        '#default_value' => $tag['first_label'] ?? '',
      ];
      $form['flagship'][$i]['first_value'] = [
        '#type' => 'number',
        '#size' => 10,
        '#default_value' => $tag['first_value'] ?? '',
      ];
      $form['flagship'][$i]['second_label'] = [
        '#type' => 'textfield',
        '#size' => 15,
        '#default_value' => $tag['second_label'] ?? '',
      ];
      $form['flagship'][$i]['second_value'] = [
        '#type' => 'number',
        '#size' => 10,
        '#default_value' => $tag['second_value'] ?? '',
      ];
      $form['flagship'][$i]['remove'] = [
        '#type' => 'submit',
        '#value' => $this->t('Remove'),
        '#name' => $i,
        '#submit' => ['::removeOneRow'],
        '#ajax' => [
          'callback' => '::getUpdateForm',
          'wrapper' => 'flagship-form-wrapper',
        ],
      ];
    }

    $form['flagship']['addmore']['add'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add one more'),
      '#submit' => ['::addOneRow'],
      '#ajax' => [
        'callback' => '::getUpdateForm',
        'wrapper' => 'flagship-form-wrapper',
      ],
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];
    return $form;
  }

  /**
   * This function is used to return the updated form after adding or removing 
   * one row. 
   * 
   * @param array $form
   *   Stores all the information about the form.
   * @param Drupal\Core\Form\FormStateInterface $form_state
   *   Stores all the values and state of the form.
   * 
   * @return array
   *   Returns the flagship form.
   */
  public function getUpdateForm(array &$form, FormStateInterface $form_state) {
    return $form['flagship'];
  }

  /**
   * This is used to add one extra row when the admin clicks on the Add one more
   * button. 
   * After setting an empty array to the form_state set the form rebuild to true
   * to rebuild the form with extra fields.
   *  
   * @param array $form
   *   Stores all the information about the form.
   * @param Drupal\Core\Form\FormStateInterface $form_state
   *   Stores all the values and state of the form.
   */
  public function addOneRow(array &$form, FormStateInterface $form_state) {
    $form_data = $form_state->get('flagship_form_values');
    $form_data[] = [
      'group_name' => '',
      'first_label' => '',
      'first_value' => '',
      'second_label' => '',
      'second_value' => '',
    ];
    $form_state->set('flagship_form_values', $form_data);
    $form_state->setRebuild();
  }

  /**
   * This function is used to remove the corresponding row when the admin clicks
   * on the Remove button.
   * At first getting the #name element of the row to determine which row to remove
   * and then remove the array from the form_state and also from the form and then
   * set the form rebuild to true to rebuild the form with a less row.
   * 
   * @param array $form
   *   Stores all the information about the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Stores all the values and state of the form.
   */
  public function removeOneRow(array &$form, FormStateInterface $form_state) {
    $trigger = $form_state->getTriggeringElement();
    $remove_index = $trigger['#name'];

    unset($form['flagship'][$remove_index]);

    $form_data = $form_state->get('flagship_form_values');
    unset($form_data[$remove_index]);
    $form_state->set('flagship_form_values', $form_data);

    // Checking if the form_state value is empty then create a empty row.
    if ($form_state->get('flagship_form_values') === []) {
      $this->addOneRow($form, $form_state);
    }
    $form_state->setRebuild();
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('flagship_form.settings');
    $form_data = $form_state->getValues()['flagship'];
    unset($form_data['addmore']);
    $config->set('data', $form_data);
    $config->save();
  }
}