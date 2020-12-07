<?php

/**
 * @file
 * Post update functions for tide_landing_page.
 */

use Drupal\paragraphs\Entity\ParagraphsType;

/**
 * Removes old paragraph types.
 */
function tide_landing_page_post_update_remove_old_paragraph_types() {
  $paragraph_types = [
    'card_navigation_featured_auto',
    'card_navigation_featured',
    'card_navigation_auto',
    'card_navigation',
  ];
  foreach ($paragraph_types as $paragraph_type) {
    $paragraph_type_entity = ParagraphsType::load($paragraph_type);
    \Drupal::entityTypeManager()
      ->getStorage('paragraphs_type')
      ->delete([$paragraph_type_entity]);
  }
}
