<?php

namespace Drupal\custom_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * This class is used to create Api for Event Node type.
 *
 * @package \Drupal\custom_api\Controller
 */
class EventNodeApiController extends ControllerBase {

  /**
   * Stores the api key to check the authentication.
   *
   * @var string
   */
  protected const API_KEY = 'EVENT_BUNDLE_API_KEY';

  /**
   * Stores the instance of Entity Type Manager Interface.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Stores the instance of Account Proxy Interface.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Initializes the object to class variables.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Stores the instance of Entity Type Manager Interface.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   Stores the instance of Account Proxy Interface.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, AccountProxyInterface $current_user) {
    $this->entityTypeManager = $entity_type_manager;
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('current_user'),
    );
  }

  /**
   * Generates the json response of Event nodes.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   Stores the object of Request Class.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   The data of the Event nodes.
   */
  public function generateApi(Request $request) {

    // Checking if the authentication key is passed to the header or the user is
    // an admin.
    if ($request->headers->get('api-key') == self::API_KEY || $this->isAdmin()) {
      $json['title'] = 'Event Nodes';
      $json['data'] = [];

      /** @var \Drupal\node\Entity\Node $nodes */
      $nodes = $this->entityTypeManager->getStorage('node')->loadByProperties(['type' => 'event_dashboard']);

      // Getting the details of the event nodes.
      foreach ($nodes as $node) {
        $json['data'][] = [
          'event_uuid' => $node->uuid->value,
          'event_title' => $node->title->value,
          'created' => date('c', $node->created->value),
          'changed' => date('c', $node->changed->value),
          'field_event_dashboard_date' => $node->field_event_dashboard_date->value,
          'field_event_dashboard_type' => [
            'value' => $node->field_event_dashboard_type->value,
            'format' => $node->field_event_dashboard_type->format,
            'processed' => $node->field_event_dashboard_type->processed,
          ],
          'field_event_dashboard_details' => [
            'value' => $node->field_event_dashboard_details->value,
            'format' => $node->field_event_dashboard_details->format,
            'processed' => $node->field_event_dashboard_details->processed,
            'summary' => $node->field_event_dashboard_details->summary,
          ],
        ];
      }
      return new JsonResponse($json);
    }
    return new JsonResponse(['error' => 'Unauthorized'], 401);
  }

  /**
   * This function is used to check if the user is an admin or not.
   *
   * @return bool
   *   Returns true if the user is an admin otherwise false.
   */
  protected function isAdmin() {
    $roles = $this->currentUser->getRoles();
    return in_array('administrator', $roles);
  }

}
