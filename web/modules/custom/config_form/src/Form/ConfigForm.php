<?php

namespace Drupal\config_form\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\config_form\FormValidator;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\Messenger;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * This is a config form that is created to take input from admin only.
 * And then store the data in the config_form.settings.yml file.
 * 
 * @package Drupal\config_form\Form
 * 
 * @author Ankit Debnath <ankit.debnath@innoraft.com>
 */
class ConfigForm extends ConfigFormBase 
{  
  /**
   * Stores the object of Custom Validation Service class.
   *
   * @var object
   */
  protected $validator;

  /**
   * Stores the object of Messenger class.
   *
   * @var object
   */
  protected $messenger;
  
  /**
   * Constructor is used to initialize the custom validation service and Messenger
   * class object to the class variable.
   *
   * @param FormValidator $validator
   *   Stores the object of FormValidator class.
   * @param Messenger $messenger
   *   Stores the object of Messenger class.
   */
  public function __construct(FormValidator $validator, Messenger $messenger) {
    $this->validator = $validator;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config_form.validator'),
      $container->get('messenger'),
    );
  }

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
      '#type' => 'textfield',
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
    $domains = ["yahoo", "gmail", "outlook", "innoraft"];

    // Validate the name of the user.
    $name_error = $this->validator->nameValidation($name);
    if ($name_error !== TRUE) {
      $form_state->setErrorByName('full_name', $name_error);
    }
    // Validate the phone number of the user.
    $phone_error = $this->validator->phoneValidation($phone);
    if ($phone_error !== TRUE) {
      $form_state->setErrorByName('phone_number', $phone_error);
    }
    // Validate the email of the user.
    $email_error = $this->validator->emailValidation($email, $domains);
    if ($email_error !== TRUE) {
      $form_state->setErrorByName('email', $email_error);
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
    $this->messenger->addMessage($this->t('Form Submitted Successfully'), 'status', TRUE);
  }
}
