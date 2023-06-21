<?php

namespace Drupal\config_form\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * This is a config form that is created to take input from admin only.
 * And then store the data in the config_form.settings.yml file.
 * 
 * @package Drupal\config_form\Form
 * 
 * @author Ankit Debnath <ankit.debnath@innoraft.com>
 */
class ConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['config_form.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'config_form_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('config_form.settings');
    $form['full_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter Your Full Name'),
      '#required' => TRUE,
    ];
    $form['phone_number'] = [
      '#type' => 'number',
      '#title' => $this->t('Enter Your Phone Number'),
      '#required' => TRUE,
    ];
    $form['email'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter Your Email Id'),
      '#required' => TRUE,
    ];
    $form['gender'] = [
      '#type' => 'radios',
      '#title' => $this->t('Gender'),
      '#options' => [
        'male' => $this->t('Male'),
        'female' => $this->t('Female'),
      ],
      '#required' => TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#button_type' => 'primary',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {   
    $name = $form_state->getValue('full_name');
    $email = $form_state->getValue('email');
    $phone = $form_state->getValue('phone_number');
    $domains = ["yahoo", "gmail", "outlook"];
    if (!preg_match('/^[A-Za-z\s]*$/', $name)) {
      $form_state->setErrorByName('name', $this->t('Please enter a valid name. Name does not contain any number or special character.'));
    }
    elseif (strlen($name) <= 3) {
      $form_state->setErrorByName('name', $this->t('Please enter a valid name. Name length is too short.'));
    }
    elseif (strlen($phone) != 10) {
      $form_state->setErrorByName('phone', $this->t('Please enter a 10 digit phone number.'));
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $form_state->setErrorByName('email', $this->t('Please enter valid email address.'));  
    }
    else {
      $pos = strpos($email, '@');
      $last_dot_position = strrpos($email, '.');
      $domain_name = substr($email, $pos + 1, $last_dot_position - $pos - 1);
      if (!in_array($domain_name, $domains)) {
        $form_state->setErrorByName('email', $this->t('Domain is not accepted. Please use public domains like gmail, yahoo, etc.'));  
      }
      $extension = substr($email, $last_dot_position);
      if ($extension != '.com') {
        $form_state->setErrorByName('email', $this->t('Only domains with .com extension is allowed.'));  
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('config_form.settings');
    $config->set('full_name', $form_state->getValue('full_name'));
    $config->set('phone_number', $form_state->getValue('phone_number'));
    $config->set('email', $form_state->getValue('email'));
    $config->set('gender', $form_state->getValue('gender'));
    $config->save();
    \Drupal::messenger()->addMessage($this->t('Form Submitted Successfully'), 'status', TRUE);
  }
}
