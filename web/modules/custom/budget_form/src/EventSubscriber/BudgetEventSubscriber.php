<?php

namespace Drupal\budget_form\EventSubscriber;

use Drupal\budget_form\Event\BudgetEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * This class is used to subscribe the budget event and show the message.
 *
 * @package Drupal\budget_form\EventSubscriber
 */
class BudgetEventSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      BudgetEvent::EVENT => 'showMessage',
    ];
  }

  /**
   * Event to show a message when viewed a movie type entity.
   *
   * This is used to show that the movie is under, over or within budget.
   *
   * @param \Drupal\budget_form\Event\BudgetEvent $event
   *   Object of Budget Event.
   */
  public function showMessage(BudgetEvent $event) {
    $budget = $event->budget;
    $price = $event->moviePrice;
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
