<?php

namespace Drupal\routing_example\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * This class is used to remove the role permission by altering the existing
 * route and restrict access of the route even if having the role permission
 * in the routing.yml file.
 */
class AlterRouting extends RouteSubscriberBase 
{

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    // Restrict access of our custom module's route.
    if ($route = $collection->get('routing_example.routing_example')) {
      $route->setRequirement('_role', 'administrator');
    }
  }
}

?>