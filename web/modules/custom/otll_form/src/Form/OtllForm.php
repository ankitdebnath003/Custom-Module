<?php

namespace Drupal\otll_form\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\UserStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * This is an OTLL form that is created to take input of user id from admin.
 *
 * It also validate the data of the form through ajax and generate the one time
 * login link and display it to the admin.
 *
 * @package Drupal\otll_form\Form
 */
class OtllForm extends FormBase {

  /**
   * Stores the object of AjaxResponse class.
   *
   * @var object
   */
  protected $response;

  /**
   * Stores the metadata of UserStorageInterface.
   *
   * @var object
   */
  private $userStorage;

  /**
   * Initialize the object of AjaxResponse class.
   */
  public function __construct(UserStorageInterface $user_storage) {
    $this->response = new AjaxResponse();
    $this->userStorage = $user_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')->getStorage('user'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'otll_form_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['user_id'] = [
      '#type' => 'number',
      '#title' => $this->t('Enter The User ID :'),
      '#prefix' => '<div id="user-id-wrapper">',
      '#suffix' => '<span id="user-id-error"></span></div>',
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#button_type' => 'primary',
      '#ajax' => [
        'callback' => '::formValidation',
      ],
      '#prefix' => '<div id="submit-wrapper">',
      '#suffix' => '<p id="submit-message"></p></div>',
    ];
    return $form;
  }

  /**
   * This function is used to take the id of the user and check if it exists.
   *
   * If exist then create a one time login link and display it to the admin.
   *
   * @param array $form
   *   Stores all the values of the fields and other informations of the form.
   * @param Drupal\Core\Form\FormStateInterface $form_state
   *   Stores all the information about the form.
   *
   * @return Response
   *   Response to the Ajax request to either show the error message or one time
   *   login link to the admin.
   */
  public function formValidation(array &$form, FormStateInterface $form_state) {
    $this->response->addCommand(new HtmlCommand('#submit-message', ''));
    $user_id = $form_state->getValue('user_id');
    if (!$user_id) {
      $this->response->addCommand(new HtmlCommand('#submit-message', 'Please Enter The User ID.'));
      $this->response->addCommand(new CssCommand('#submit-message', ['color' => 'red']));
    }
    elseif ($user = $this->userStorage->load($user_id)) {
      $link = user_pass_reset_url($user) . '/login';
      $this->response->addCommand(new HtmlCommand('#submit-message', $link));
      $this->response->addCommand(new CssCommand('#submit-message', ['color' => 'green']));
    }
    else {
      $this->response->addCommand(new HtmlCommand('#submit-message', 'User ID is invalid. Please enter a valid user ID.'));
      $this->response->addCommand(new CssCommand('#submit-message', ['color' => 'red']));
    }
    return $this->response;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

}
