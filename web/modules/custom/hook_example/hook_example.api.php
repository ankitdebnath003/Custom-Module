<?php

/**
 * @file
 * Structure of a custom hook.
 */

/**
 * Respond to node view count being incremented.
 *
 * This hooks allows modules to respond whenever the total number of times the
 * current user has viewed a specific node during their current session is
 * increased.
 *
 * @param int $current_count
 *   The number of times that the current user has viewed the node during this
 *   session.
 * @param EntityInterface $entity
 *   The node being viewed.
 */
function hook_count_incremented(int $current_count, object $entity) {
  // Further implementation here.
}
