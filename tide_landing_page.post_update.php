<?php

/**
 * @file
 * Post update functions for tide_landing_page.
 */

use Drupal\paragraphs\Entity\Paragraph;
use Drupal\paragraphs\Entity\ParagraphsType;

/**
 * Removes older paragraph entities.
 */
function tide_landing_page_post_update_remove_old_paragraph_entities(&$sandbox) {
  $paragraph_types = [
    'card_navigation_featured_auto',
    'card_navigation_featured',
    'card_navigation_auto',
    'card_navigation',
    'card_promotion',
    'card_promotion_auto',
  ];
  if (!isset($sandbox['total'])) {
    $query = \Drupal::entityTypeManager()
      ->getStorage('paragraph')
      ->getQuery('OR');
    foreach ($paragraph_types as $paragraph_type) {
      $condition = $query->andConditionGroup()
        ->condition('type', $paragraph_type);
      $query->condition($condition);
    }
    $count = $query->count()->execute();
    $sandbox['total'] = $count;
    $sandbox['current'] = 0;
    $sandbox['processed'] = 0;
    $sandbox['#finished'] = $count ? 0 : 1;
  }
  $batch_size = 10;
  $query = \Drupal::entityTypeManager()
    ->getStorage('paragraph')
    ->getQuery('OR');
  foreach ($paragraph_types as $paragraph_type) {
    $condition = $query->andConditionGroup()
      ->condition('type', $paragraph_type);
    $query->condition($condition);
  }
  $paragraph_ids = $query->condition('id', $sandbox['current'], '>')
    ->sort('id', 'ASC')
    ->range(0, $batch_size)
    ->execute();
  foreach ($paragraph_ids as $paragraph_id) {
    $sandbox['current'] = $paragraph_id;
    $paragraph = Paragraph::load($paragraph_id);
    $paragraph->delete();
    $sandbox['processed']++;
  }
  $sandbox['#finished'] = $sandbox['total'] ? $sandbox['processed'] / $sandbox['total'] : 1;
  $sandbox['#finished'] = $sandbox['#finished'] > 1 ? 1 : $sandbox['#finished'];
}

/**
 * Removes old paragraph types.
 */
function tide_landing_page_post_update_remove_old_paragraph_types() {
  $paragraph_types = [
    'card_navigation_featured_auto',
    'card_navigation_featured',
    'card_navigation_auto',
    'card_navigation',
    'card_promotion',
    'card_promotion_auto',
  ];
  foreach ($paragraph_types as $paragraph_type) {
    $paragraph_type_entity = ParagraphsType::load($paragraph_type);
    \Drupal::entityTypeManager()
      ->getStorage('paragraphs_type')
      ->delete([$paragraph_type_entity]);
  }
}
