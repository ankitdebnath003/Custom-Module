<?php

namespace Drupal\budget_form\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * This class is used show the message.
 *
 * @package Drupal\budget_form\EventSubscriber
 */
class BudgetEventSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      KernelEvents::VIEW => ['showMessage', 10],
    ];
  }

  /**
   * Event to show a message when viewed a movie type entity.
   *
   * This is used to show that the movie is under, over or within budget.
   *
   * @param \Symfony\Component\HttpKernel\Event\ViewEvent $event
   *   Object of View Event.
   */
  public function showMessage(ViewEvent $event) {
    $node = $event->getControllerResult();

    // Checking the page is a node type or not and also its movie bundle type.
    if (isset($node['#node']) && $node['#node']->gettype() == 'movie_entity') {
      $budget = \Drupal::configFactory()->get('movie_budget_form.settings')->get('budget');

      // Checking if the budget is null or not and also if the content has any
      // movie price value or not.
      if ($budget && $price = $node['#node']->get('field_movie_entity_number')->value) {
        if ($budget > $price) {
          \Drupal::messenger()->addMessage('The movie is under budget');
        }
        elseif ($budget < $price) {
          \Drupal::messenger()->addMessage('The movie is over budget');
        }
        else {
          \Drupal::messenger()->addMessage('The movie is within budget');
        }
      }
    }
  }

}
