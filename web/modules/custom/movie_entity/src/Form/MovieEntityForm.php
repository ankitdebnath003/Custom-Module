<?php

namespace Drupal\movie_entity\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Movie entity form.
 *
 * @property \Drupal\movie_entity\MovieEntityInterface $entity
 */
class MovieEntityForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $id = $this->entity->get('movieName')[0]['target_id'] ?? '';
    $form = parent::form($form, $form_state);
    $form['movieName'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Movie Name'),
      '#target_type' => 'node',
      '#selection_settings' => ['target_bundles' => ['movie_entity']],
      '#default_value' => $this->entityTypeManager->getStorage('node')->load($id) ?? '',
      '#tags' => TRUE,
    ];
    $form['year'] = [
      '#type' => 'date',
      '#title' => $this->t('Award Winning Year'),
      '#default_value' => $this->entity->get('year'),
      '#size' => 30,
      '#maxlength' => 1024,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;

    if ($entity->isNew()) {
      // Get the last entity ID from the storage.
      // $query = \Drupal::entityQuery('movie_entity')
      $query = $this->entityTypeManager->getStorage('movie_entity')->getQuery()
        ->sort('id', 'DESC')
        ->range(0, 1);
      $last_id = $query->execute();

      // Increment the last ID and set it as the new entity ID.
      if (!empty($last_id)) {
        $last_id = array_shift($last_id);
        $new_id = $last_id + 1;
      }
      else {
        $new_id = 0;
      }
      $entity->set('id', $new_id);
    }

    $entity->save();

    $message_args = ['%label' => $entity->label()];
    $message = $entity->isNew()
      ? $this->t('Created new movie entity %label.', $message_args)
      : $this->t('Updated movie entity %label.', $message_args);

    $this->messenger()->addStatus($message);
    $form_state->setRedirectUrl($entity->toUrl('collection'));
  }

}
