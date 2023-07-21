<?php

namespace Drupal\movie_entity\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form to select the movie name and the award winning year.
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
    $this->entity->set('id', $this->entity->get('uuid'));
    $this->entity->save();

    $message_args = ['%label' => $this->entity->label()];
    $message = $this->entity->isNew()
      ? $this->t('Created new movie entity %label.', $message_args)
      : $this->t('Updated movie entity %label.', $message_args);

    $this->messenger()->addStatus($message);
    $form_state->setRedirectUrl($this->entity->toUrl('collection'));
  }

}
