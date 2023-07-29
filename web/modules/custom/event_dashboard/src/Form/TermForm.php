<?php

namespace Drupal\event_dashboard\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\mysql\Driver\Database\mysql\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form to select the taxonomy term to get the details of it.
 *
 * For showing the details of the taxonomy terms we alias the term id with its
 * name and pass it to the controller from where the details will be shown.
 *
 * @package Drupal\event_dashboard\Form
 */
class TermForm extends FormBase {

  /**
   * Stores the instance of EntityTypeManagerInterface.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Stores the instance of Database Connection.
   *
   * @var \Drupal\mysql\Driver\Database\mysql\Connection
   */
  protected $connection;

  /**
   * Stores the array of the term names of Event Vocabulary.
   *
   * @var array
   */
  protected $termNames;

  /**
   * Initializes the object to class variables.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Stores the instance of EntityTypeManagerInterface.
   * @param \Drupal\Core\Database\Driver\corefake\Connection $connection
   *   Stores the object of database connection.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, Connection $connection) {
    $this->entityTypeManager = $entity_type_manager;
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('database'),
      $container->get('url_generator'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'event_dashboard_term';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $this->getTaxonomyTermOptions('event');
    $form['terms'] = [
      '#type' => 'select',
      '#title' => $this->t('Select the Term'),
      '#target_type' => 'taxonomy_term',
      '#selection_settings' => [
        'target_bundles' => ['event'],
      ],
      '#options' => ['' => $this->t('- None -')] + $this->termNames,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Send'),
    ];

    return $form;
  }

  /**
   * Used to fetch all the taxonomy term names by the vocabulary name.
   *
   * @param string $vocabulary_name
   *   Stores the vocabulary name.
   */
  public function getTaxonomyTermOptions(string $vocabulary_name) {
    $terms = $this->entityTypeManager
      ->getStorage('taxonomy_term')
      ->loadTree($vocabulary_name);

    foreach ($terms as $term) {
      $this->termNames[$term->tid] = $term->name;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (!$form_state->getValue('terms')) {
      $form_state->setErrorByName('terms', 'Please select a term');
    }
  }

  /**
   * Used to set path alias for the taxonomy term if not set.
   *
   * @param int $tid
   *   The term id.
   * @param string $term_name
   *   The term name.
   */
  public function setPathAlias(int $tid, string $term_name) {
    if ($this->checkExistingAlias($tid)) {
      $path_alias = $this->entityTypeManager->getStorage('path_alias')->create([
        'path' => '/term-show/' . $tid,
        'alias' => '/term-show/' . $term_name,
      ]);
      $path_alias->save();
    }
  }

  /**
   * Used to check if the path is already aliased.
   *
   * @param int $tid
   *   The term id.
   *
   * @return bool
   *   Returns True if the query result gives empty array otherwise False.
   */
  public function checkExistingAlias(int $tid) {
    $query = $this->connection->select('path_alias', 'n')
      ->fields('n', ['path'])
      ->condition('path', '/term-show/' . $tid)
      ->execute()
      ->fetchAll();

    return $query == [];
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $tid = $form_state->getValue('terms');
    $term_name = $this->termNames[$tid];
    $this->setPathAlias($tid, $term_name);

    $path = '/term-show/' . $tid;
    $url = Url::fromUserInput($path);
    $form_state->setRedirectUrl($url);
  }

}
