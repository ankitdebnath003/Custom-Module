<?php

namespace Drupal\movie_entity\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\movie_entity\MovieEntityInterface;

/**
 * Defines the movie entity entity type.
 *
 * @ConfigEntityType(
 *   id = "movie_entity",
 *   label = @Translation("Movie entity"),
 *   label_collection = @Translation("Movie entities"),
 *   label_singular = @Translation("movie entity"),
 *   label_plural = @Translation("movie entities"),
 *   label_count = @PluralTranslation(
 *     singular = "@count movie entity",
 *     plural = "@count movie entities",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\movie_entity\MovieEntityListBuilder",
 *     "form" = {
 *       "add" = "Drupal\movie_entity\Form\MovieEntityForm",
 *       "edit" = "Drupal\movie_entity\Form\MovieEntityForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     }
 *   },
 *   config_prefix = "movie_entity",
 *   admin_permission = "administer movie_entity",
 *   links = {
 *     "collection" = "/admin/structure/movie-entity",
 *     "add-form" = "/admin/structure/movie-entity/add",
 *     "edit-form" = "/admin/structure/movie-entity/{movie_entity}",
 *     "delete-form" = "/admin/structure/movie-entity/{movie_entity}/delete"
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "movieName" = "movieName",
 *     "year" = "year",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "movieName",
 *     "year",
 *   }
 * )
 */
class MovieEntity extends ConfigEntityBase implements MovieEntityInterface {

  /**
   * The movie entity ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The movie entity label.
   *
   * @var string
   */
  protected $label;

  /**
   * The movie entity Name.
   *
   * @var object
   */
  protected $movieName;

  /**
   * The movie entity year.
   *
   * @var datetime
   */
  protected $year;

}
