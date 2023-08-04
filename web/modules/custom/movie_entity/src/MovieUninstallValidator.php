<?php

namespace Drupal\movie_entity;

use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Extension\ModuleUninstallValidatorInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;

/**
 * Prevents blog module from being uninstalled whilst any blog nodes exist.
 */
class MovieUninstallValidator implements ModuleUninstallValidatorInterface {

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
   * Helper function to determine if a user has blog posts already.
   *
   * @param \Drupal\user\UserInterface|null $account
   *   Blog post owner user, or NULL.
   *
   * @return string
   *   Count of blog posts.
   */
  protected function moviePostCounter($account = NULL) {
    $query = $this->entityTypeManager->getStorage('node')->getQuery()
      ->condition('type', 'movie_entity')
      ->condition('status', 1)
      ->accessCheck(TRUE);
    if ($account !== NULL) {
      $query->condition('uid', $account->id());
    }
    return $query->count()
      ->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function validate($module) {
    $reasons = [];
    if ($module === 'movie_entity' && $this->moviePostCounter() != 0) {
      $reasons[] = $this->t('To uninstall Movie module, first delete all <em>Movie</em> content.');
    }
    return $reasons;
  }

}
