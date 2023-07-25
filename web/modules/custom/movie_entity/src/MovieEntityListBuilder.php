<?php

namespace Drupal\movie_entity;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

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
    $id = $entity->get('movieName')[0]['target_id'];
    $item = \Drupal::entityTypeManager()->getStorage('node')->load($id);
    $row['movieName'] = $item->label() ?? '';
    $row['year'] = $entity->get('year');
    return $row + parent::buildRow($entity);
  }

}
