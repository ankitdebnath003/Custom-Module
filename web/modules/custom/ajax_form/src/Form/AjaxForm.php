<?php

namespace Drupal\ajax_form\Form;

use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Messenger\Messenger;
use Drupal\config_form\FormValidator;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * This is a config form that is created to take input from admin only and also
 * validate and store the data of the form through ajax and store the data in the 
 * ajax_config_form.settings.yml file.
 * 
 * @package Drupal\ajax_form\Form
 * 
 * @author Ankit Debnath <ankit.debnath@innoraft.com>
 */
class AjaxForm extends ConfigFormBase 
{
  /**
   * Stores the object of AjaxResponse class to handle the form validation and 
   * submission through Ajax.
   *
   * @var object
   */
  protected $response;
  
  /**
   * Stores the object of FormValidator class to handle the form validation.
   *
   * @var object
   */
  protected $validator;
  
  /**
   * Constructor is used to initialize the object of AjaxResponse, FormValidator
   * and Messenger class to the class variable.
   * 
   * @param FormValidator $validator
   *   Stores the object of FormValidator Class.
   * @param Messenger $messenger
   *   Stores the object of Messenger Class.
   */
  public function __construct(FormValidator $validator, Messenger $messenger) {
    $this->response = new AjaxResponse();
    $this->validator = $validator;
    $messenger->deleteByType('error');
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
      '#required' => TRUE,
			'#title' => $this->t('Enter Your Full Name :'),
      '#default_value' => $config->get('full_name'),
			'#prefix' => '<div id="full-name-wrapper">',
    	'#suffix' => '<span id="full-name-error"></span></div>',
    ];

    $form['phone_number'] = [
      '#type' => 'textfield',
      '#size' => 15,
      '#required' => TRUE,
      '#title' => $this->t('Enter Your Phone Number :'),
      '#default_value' => $config->get('phone_number'),
			'#prefix' => '<div id="phone-wrapper"',
    	'#suffix' => '<span id="phone-error"></span></div>',
    ];
    $form['email'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Enter Your Email Id :'),
      '#default_value' => $config->get('email'),
			'#prefix' => '<div id="email-wrapper">',
    	'#suffix' => '<span id="email-error"></span></div>',
    ];
    $form['gender'] = [
      '#type' => 'radios',
      '#required' => TRUE,
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
   * This function is used to Validate each field of the config form and show the
   * error message if any or stores the value of the form to the database.
   *
   * @param array $form
   *   Stores all the values of the fields and other informations of the form.
   * @param FormStateInterface $form_state
   *   Stores all the information about the form.
   * 
   * @return Response
   *   Response to the Ajax request to make changes in the form.
   */
  public function formValidation(array &$form, FormStateInterface $form_state) {    
    $domains = ["yahoo", "gmail", "outlook", "innoraft"];
    $this->response->addCommand(new HtmlCommand('#full-name-error, #email-error, #phone-error, #gender-error, #submit-message', ''));
    $this->response->addCommand(new CssCommand('#edit-email, #edit-full-name, #edit-phone-number', ['border' => '']));

    // Checking if the full name is valid or not.
    $name_error = $this->validator->nameValidation($form_state->getValue('full_name'));
    if ($name_error !== TRUE) {
      $this->response->addCommand(new HtmlCommand('#full-name-error', $name_error));
    }
    else {
      $this->response->addCommand(new CssCommand('#edit-full-name', ['border' => '2px solid green']));
    }

    // Checking if the phone number is valid or not.
    $phone_error = $this->validator->phoneValidation($form_state->getValue('phone_number'));
    if ($phone_error !== TRUE) {
      $this->response->addCommand(new HtmlCommand('#phone-error', $phone_error));
    }
    else {
      $this->response->addCommand(new CssCommand('#edit-phone-number', ['border' => '2px solid green']));
    }

    // Checking if the email address is valid or not.
    $email_error = $this->validator->emailValidation($form_state->getValue('email'), $domains);
    if ($email_error !== TRUE) {
      $this->response->addCommand(new HtmlCommand('#email-error', $email_error));
    }
    else {
      $this->response->addCommand(new CssCommand('#edit-email', ['border' => '2px solid green']));
    }

    // Checking if the gender is selected or not.
    $gender_error = $this->validator->genderValidation($form_state->getValue('gender'));
    if ($gender_error !== TRUE) {
      $this->response->addCommand(new HtmlCommand('#gender-error', $gender_error));
    }

    // Checking if all the validations are passed. Then call the function to store 
    // the values of the form in the database.
    if ($name_error === TRUE && $phone_error === TRUE && $email_error === TRUE && $gender_error === TRUE) {
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
