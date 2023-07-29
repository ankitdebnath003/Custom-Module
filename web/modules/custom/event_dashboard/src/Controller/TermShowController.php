<?php

namespace Drupal\event_dashboard\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\mysql\Driver\Database\mysql\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * This controller gets all the information about the term and display it.
 *
 * @package Drupal\event_dashboard\Controller
 */
class TermShowController extends ControllerBase {

  /**
   * Stores the instance of EntityTypeManagerInterface.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Stores the instance of Database connection.
   *
   * @var \Drupal\mysql\Driver\Database\mysql\Connection
   */
  protected $connection;

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
    );
  }

  /**
   * {@inheritdoc}
   */
  public function showAllTermDetails(Request $rq) {
    $tid = $rq->get('id');

    // Checking if the url is valid or not.
    if (ctype_digit($tid)) {
      $term = $this->entityTypeManager->getStorage('taxonomy_term')->load($tid);
      $query = $this->connection->select('taxonomy_index', 'n')
        ->fields('n', ['nid'])
        ->condition('tid', $tid)
        ->execute()
        ->fetchAll();

      // If query gives null then no nodes have the term. So return it here.
      if (!$query) {
        return [
          '#theme' => 'term',
          '#title' => $this->t('@term Details', ['@term' => $term->label()]),
          '#term_id' => $term->id(),
          '#term_uuid' => $term->uuid(),
          '#attached' => [
            'library' => [
              'event_dashboard/term',
            ],
          ],
          '#cache' => [
            'tags' => ['node_list', 'taxonomy_term_list:event'],
          ],
        ];
      }

      // Getting all the node details to show.
      foreach ($query as $item) {
        $node = $this->entityTypeManager->getStorage('node')->load($item->nid);
        $node_details[] = [
          'title' => $node->label(),
          'link' => $node->toUrl('canonical', ['absolute' => TRUE])->toString(),
        ];
      }

      return [
        '#theme' => 'term',
        '#title' => $this->t('@term Details', ['@term' => $term->label()]),
        '#term_id' => $term->id(),
        '#term_uuid' => $term->uuid(),
        '#node_details' => $node_details,
        '#attached' => [
          'library' => [
            'event_dashboard/term',
          ],
        ],
        '#cache' => [
          'tags' => ['node_list', 'taxonomy_term_list:event'],
        ],
      ];
    }
    throw new NotFoundHttpException();
  }

}
