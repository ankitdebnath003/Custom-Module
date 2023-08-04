<?php

namespace Drupal\event_dashboard;

use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Extension\ModuleUninstallValidatorInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;

/**
 * Prevents event module from being uninstalled whilst any event nodes exist.
 */
class EventUninstallValidator implements ModuleUninstallValidatorInterface {

  use StringTranslationTrait;

  /**
   * Stores the object of Entity Type Manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * Constructs a new validator.
   *
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The string translation service.
   * @param \Drupal\Core\Entity\EntityTypeManager $entity_type_manager
   *   The Entity Type Manager service.
   */
  public function __construct(TranslationInterface $string_translation, EntityTypeManager $entity_type_manager) {
    $this->setStringTranslation($string_translation);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * Helper function to determine if a user has any content of event type.
   *
   * @param \Drupal\user\UserInterface|null $account
   *   Blog post owner user, or NULL.
   *
   * @return string
   *   Count of blog posts.
   */
  protected function eventPostCounter($account = NULL) {
    $query = $this->entityTypeManager->getStorage('node')->getQuery()
      ->condition('type', 'event_dashboard')
      ->condition('status', 1)
      ->accessCheck(TRUE);
    if ($account !== NULL) {
      $query->condition('uid', $account->id());
    }
    return $query->count()->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function validate($module) {
    $reasons = [];
    if ($module === 'event_dashboard' && $this->eventPostCounter() != 0) {
      $reasons[] = $this->t('To uninstall Event Dashboard module, first delete all <em>Event</em> content.');
    }
    return $reasons;
  }

}
