<?php

namespace Drupal\movie_entity;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Config\Entity\ConfigEntityStorage;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a listing of movie entities.
 */
class MovieEntityListBuilder extends ConfigEntityListBuilder {

  /**
   * Stores the instance of EntityTypeManagerInterface.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Initializes objects to class variables.
   *
   * @param object $entity_type
   *   Stores the instance of EntityTypeInterface.
   * @param object $entity
   *   Stores the instance of ConfigEntityInterface.
   * @param object $entity_type_manager
   *   Stores the instance of EntityTypeManagerInterface.
   */
  public function __construct(EntityTypeInterface $entity_type, ConfigEntityStorage $entity, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($entity_type, $entity);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('entity_type.manager')->getStorage($entity_type->id()),
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['movieName'] = $this->t('Movie Name');
    $header['year'] = $this->t('Award Winning Year');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\movie_entity\MovieEntityInterface $entity */
    $ids = $entity->get('movieName') ?? [];
    $movies = [];

    // Fetching each movie's names.
    foreach ($ids as $id) {
      $movie = $this->entityTypeManager->getStorage('node')->load($id['target_id']);
      if ($movie) {
        $movies[] = $movie->label();
      }
    }

    $row['movieName'] = implode(', ', $movies);
    $row['year'] = $entity->get('year');
    return $row + parent::buildRow($entity);
  }

}
