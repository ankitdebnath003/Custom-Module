<?php

namespace Drupal\event_dashboard\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\mysql\Driver\Database\mysql\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * This controller gets all the information about events and display it.
 *
 * @package Drupal\event_dashboard\Controller
 */
class EventDashboardController extends ControllerBase {

  /**
   * Stores the object of Entity Type Manager.
   *
   * @var object
   */
  protected $entityTypeManager;

  /**
   * Stores the object of Database Connection.
   *
   * @var object
   */
  protected $database;

  /**
   * Initializes the object to class variables.
   *
   * @param object $entity_type_manager
   *   Stores the object of Entity Type Manager.
   * @param object $database
   *   Stores the object of Database Connection.
   */
  public function __construct(EntityTypeManager $entity_type_manager, Connection $database) {
    $this->entityTypeManager = $entity_type_manager;
    $this->database = $database;
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
   * Used to get all the details of the events and display it.
   */
  public function eventDetails() {

    // Checking if any event exists.
    if ($this->isEventExist()) {
      return [
        '#markup' => '<h2>There are no events.</h2>',
        '#cache' => [
          'tags' => ['node_list:event_dashboard'],
        ],
      ];
    }

    $dates = $this->getAllDates();
    $build['content'] = [
      '#theme' => 'event_dashboard',
      '#title' => 'Event Dashboard',
      '#years' => $this->getEventsYearly($dates),
      '#quarters' => $this->groupEventsByQuarter($dates),
      '#event_types' => $this->getEventTypeCounts(),
      '#attached' => [
        'library' => [
          'event_dashboard/event-dashboard',
        ],
      ],
      '#cache' => [
        'tags' => ['node_list:event_dashboard'],
      ],
    ];
    return $build;
  }

  /**
   * Used to check if there is any content of type event.
   *
   * @return bool
   *   Based on the query.
   */
  public function isEventExist() {
    $query = $this->entityTypeManager->getStorage('node')->getQuery()
      ->condition('type', 'event_dashboard')
      ->condition('status', 1)
      ->accessCheck(TRUE)
      ->execute();
    return $query === [];
  }

  /**
   * Used to fetch the dates of all events from the database.
   *
   * @return array
   *   The dates of events.
   */
  public function getAllDates() {
    $query = $this->database->select('node__field_event_dashboard_date', 'n');
    $query->addField('n', ' field_event_dashboard_date_value');
    return $query->execute()->fetchAll();
  }

  /**
   * Used to get the counts of events yearly.
   *
   * @param array $dates
   *   The event dates fetch from the database.
   *
   * @return array
   *   The counts of events yearly.
   */
  public function getEventsYearly(array $dates) {
    $event_year = [];
    foreach ($dates as $date) {
      $timestamp = strtotime($date->field_event_dashboard_date_value);
      $year = date('Y', $timestamp);
      $event_year[$year] = isset($event_year[$year]) ? $event_year[$year] + 1 : 1;
    }
    return $event_year;
  }

  /**
   * Used to get the count of events in each quarter of a year.
   *
   * @param array $events
   *   Stores all the event dates.
   *
   * @return array
   *   Counts of events in each quarter of a year.
   */
  public function groupEventsByQuarter(array $events) {
    $quarters = [];
    $quarter = [
      1 => 'Jan to March',
      2 => 'April to June',
      3 => 'July to Sept',
      4 => 'Oct to Dec',
    ];

    // Setting the counts of events in each quarter of a year.
    foreach ($events as $event) {
      $dateString = $event->field_event_dashboard_date_value;
      $timestamp = strtotime($dateString);
      $quarter_no = ceil(date('n', $timestamp) / 3);
      $year = date('Y', $timestamp);

      if (!isset($quarters[$year])) {
        $quarters[$year] = [
          $quarter[1] => 0,
          $quarter[2] => 0,
          $quarter[3] => 0,
          $quarter[4] => 0,
        ];
      }

      $quarters[$year][$quarter[$quarter_no]] += 1;
    }

    return $quarters;
  }

  /**
   * Used to fetch the types of all events from the database.
   *
   * @return array
   *   The types of events.
   */
  public function getEventTypeCounts() {
    $event_types = $this->getAllEventTypes();
    $eventCounts = [];
    foreach ($event_types as $event) {
      $eventType = ucfirst($event->field_event_dashboard_type_value);
      $eventCounts[$eventType] = isset($eventCounts[$eventType]) ? $eventCounts[$eventType] + 1 : 1;
    }
    return $eventCounts;
  }

  /**
   * Used to fetch the types of all events from the database.
   *
   * @return array
   *   The types of events.
   */
  public function getAllEventTypes() {
    $query = $this->database->select('node__field_event_dashboard_type', 'n');
    $query->addField('n', ' field_event_dashboard_type_value');
    return $query->execute()->fetchAll();
  }

}
