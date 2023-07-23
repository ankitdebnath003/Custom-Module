<?php

namespace Drupal\budget_form\Event;

use Drupal\Component\EventDispatcher\Event;

/**
 * A custom event which is used whenever a movie node is viewed.
 *
 * @package Drupal\budget_form\Event
 */
class BudgetEvent extends Event {

  /**
   * The name of the event.
   *
   * @var string
   */
  const EVENT = 'budget';

  /**
   * The budget of the movie.
   *
   * @var int
   */
  public $budget;

  /**
   * The price of the movie.
   *
   * @var int
   */
  public $moviePrice;

  /**
   * Stores the values to the class variable.
   *
   * @param int $budget
   *   The movie budget from the config form.
   * @param int $movie_price
   *   The movie price from the movie node.
   */
  public function __construct(int $budget, int $movie_price) {
    $this->budget = $budget;
    $this->moviePrice = $movie_price;
  }

}
