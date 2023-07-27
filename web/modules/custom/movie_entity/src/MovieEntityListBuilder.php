<?php

namespace Drupal\movie_entity;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\node\Entity\Node;

/**
 * Provides a listing of movie entities.
 */
class MovieEntityListBuilder extends ConfigEntityListBuilder {

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
      $movie = Node::load($id['target_id']);
      if ($movie) {
        $movies[] = $movie->label();
      }
    }

    $row['movieName'] = implode(', ', $movies);
    $row['year'] = $entity->get('year');
    return $row + parent::buildRow($entity);
  }

}
