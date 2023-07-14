<?php

namespace Drupal\ajax_form\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * This is a config form that is created to take input from admin only.
 *
 * It also validate and store the data of the form through ajax and store the
 * data in the ajax_config_form.settings.yml file.
 *
 * @package Drupal\ajax_form\Form
 */
class AjaxForm extends ConfigFormBase {

  /**
   * Stores the object of AjaxResponse class.
   *
   * @var object
   */
  private $response;

  /**
   * Initialize the object of AjaxResponse class to the class variable.
   */
  public function __construct() {
    $this->response = new AjaxResponse();
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['ajax_config_form.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ajax_config_form_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('ajax_config_form.settings');
    $form['full_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter Your Full Name :'),
      '#default_value' => $config->get('full_name'),
      '#prefix' => '<div id="full-name-wrapper">',
      '#suffix' => '<span id="full-name-error"></span></div>',
    ];

    $form['phone_number'] = [
      '#type' => 'textfield',
      '#size' => 15,
      '#title' => $this->t('Enter Your Phone Number :'),
      '#default_value' => $config->get('phone_number'),
      '#prefix' => '<div id="phone-wrapper"',
      '#suffix' => '<span id="phone-error"></span></div>',
    ];
    $form['email'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter Your Email Id :'),
      '#default_value' => $config->get('email'),
      '#prefix' => '<div id="email-wrapper">',
      '#suffix' => '<span id="email-error"></span></div>',
    ];
    $form['gender'] = [
      '#type' => 'radios',
      '#title' => $this->t('Gender'),
      '#default_value' => $config->get('gender'),
      '#options' => [
        'male' => $this->t('Male'),
        'female' => $this->t('Female'),
      ],
      '#prefix' => '<div id="gender-wrapper">',
      '#suffix' => '<span id="gender-error"></span></div>',
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#button_type' => 'primary',
      '#ajax' => [
        'callback' => '::formValidation',
      ],
      '#prefix' => '<div id="submit-wrapper">',
      '#suffix' => '<span id="submit-message"></span></div>',
    ];
    return $form;
  }

  /**
   * Thisk function is used to validate the name.
   *
   * @param string $name
   *   Stores the full name from the form.
   *
   * @return bool
   *   Based on the validation of the name.
   */
  public function nameValidation(string $name) {
    if (!strlen($name)) {
      $this->response->addCommand(new HtmlCommand('#full-name-error', 'Please Enter Your Name.'));
    }
    elseif (!preg_match('/^[A-Za-z\s]*$/', $name)) {
      $this->response->addCommand(new HtmlCommand('#full-name-error', 'Please enter a valid name. Name does not contain any number or special character.'));
    }
    elseif (strlen($name) <= 3) {
      $this->response->addCommand(new HtmlCommand('#full-name-error', 'Please enter a valid name. Name length is too short.'));
    }
    else {
      $this->response->addCommand(new CssCommand('#edit-full-name', ['border' => '2px solid green']));
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Thisk function is used to validate the phone number.
   *
   * @param string $phone
   *   Stores the phone number from the form.
   *
   * @return bool
   *   Based on the validation of the phone.
   */
  public function phoneValidation(string $phone) {
    if (!strlen($phone)) {
      $this->response->addCommand(new HtmlCommand('#phone-error', 'Please Enter Your Phone Number.'));
    }
    elseif (!preg_match('/^\+91[6-9][0-9]{9}$/', $phone)) {
      $this->response->addCommand(new HtmlCommand('#phone-error', 'Please enter a 10 digit indian phone number starting with +91.'));
    }
    else {
      $this->response->addCommand(new CssCommand('#edit-phone-number', ['border' => '2px solid green']));
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Thisk function is used to validate the email.
   *
   * @param string $email
   *   Stores the email from the form.
   *
   * @return bool
   *   Based on the validation of the email.
   */
  public function emailValidation(string $email) {
    $domains = ["yahoo", "gmail", "outlook", "innoraft"];
    if (!strlen($email)) {
      $this->response->addCommand(new HtmlCommand('#email-error', 'Please Enter Your Email ID.'));
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $this->response->addCommand(new HtmlCommand('#email-error', 'Please enter valid email address.'));
    }
    else {
      $pos = strpos($email, '@');
      $last_dot_position = strrpos($email, '.');
      $domain_name = substr($email, $pos + 1, $last_dot_position - $pos - 1);
      $extension = substr($email, $last_dot_position);
      if (!in_array($domain_name, $domains)) {
        $this->response->addCommand(new HtmlCommand('#email-error', 'Domain is not accepted. Please use public domains like gmail, yahoo, etc.'));
      }
      elseif ($extension != '.com') {
        $this->response->addCommand(new HtmlCommand('#email-error', 'Only domains with .com extension is allowed.'));
      }
      else {
        $this->response->addCommand(new CssCommand('#edit-email', ['border' => '2px solid green']));
        return TRUE;
      }
      return FALSE;
    }
  }

  /**
   * Thisk function is used to validate the gender.
   *
   * @param mixed $gender
   *   Stores either the gender or NULL if nothing from the radio options has
   *   been selected from the form.
   *
   * @return bool
   *   Based on the validation of the gender.
   */
  public function genderValidation(mixed $gender) {
    if (!$gender) {
      $this->response->addCommand(new HtmlCommand('#gender-error', 'Please Select Your Gender.'));
      return FALSE;
    }
    return TRUE;
  }

  /**
   * This function is used to Validate each field of the config form.
   *
   * It also shows the error message if any or stores the value of the form to
   * the database.
   *
   * @param array $form
   *   Stores all the values of the fields and other informations of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Stores all the information about the form.
   *
   * @return Response
   *   Response to the Ajax request to make changes in the form.
   */
  public function formValidation(array &$form, FormStateInterface $form_state) {
    $this->response->addCommand(new HtmlCommand('#full-name-error, #email-error, #phone-error, #gender-error, #submit-message', ''));
    $this->response->addCommand(new CssCommand('#edit-email, #edit-full-name, #edit-phone-number', ['border' => '']));
    $f1 = $this->nameValidation($form_state->getValue('full_name'));
    $f2 = $this->phoneValidation($form_state->getValue('phone_number'));
    $f3 = $this->emailValidation($form_state->getValue('email'));
    $f4 = $this->genderValidation($form_state->getValue('gender'));

    // Checking if all the validations are passed. Then call the function to
    // store the values of the form in the database.
    if ($f1 && $f2 && $f3 && $f4) {
      $this->response->addCommand(new HtmlCommand('#submit-message', 'Form Submitted Successfully.'));
      $this->response->addCommand(new CssCommand('#submit-message', ['color' => 'green']));
      $this->submit($form_state);
    }
    $this->response->addCommand(new CssCommand('#email-error, #full-name-error, #phone-error, #gender-error', ['color' => 'red']));
    return $this->response;
  }

  /**
   * This function is used to store all the values of the form in the database.
   *
   * @param Drupal\Core\Form\FormStateInterface $form_state
   *   Stores all the information of the form.
   */
  public function submit(FormStateInterface $form_state) {
    $config = $this->config('ajax_config_form.settings');
    $config->set('full_name', $form_state->getValue('full_name'));
    $config->set('phone_number', $form_state->getValue('phone_number'));
    $config->set('email', $form_state->getValue('email'));
    $config->set('gender', $form_state->getValue('gender'));
    $config->save();
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

}
