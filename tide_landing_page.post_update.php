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
  if (getenv("LAGOON_ENVIRONMENT_TYPE") != 'production') {
    return;
  }
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

  // Currently, we don't delete cards and call to action.
  $config = \Drupal::configFactory()
    ->getEditable('field.field.node.landing_page.field_landing_page_component');
  $settings = $config->get('settings');
  $card_and_CTA = [
    'card_promotion',
    'card_promotion_auto',
    'card_event',
    'card_event_auto',
  ];
  foreach ($card_and_CTA as $item) {
    if (isset($settings['handler_settings']['target_bundles'][$item])) {
      unset($settings['handler_settings']['target_bundles'][$item]);
    }
    if (isset($settings['handler_settings']['target_bundles_drag_drop'][$item]['enabled'])) {
      if ($settings['handler_settings']['target_bundles_drag_drop'][$item]['enabled'] == TRUE) {
        $settings['handler_settings']['target_bundles_drag_drop'][$item]['enabled'] = FALSE;
      }
    }
  }
  $config->set('settings', $settings)->save();
}

/**
 * Removes featured_news paragraph type.
 */
function tide_landing_page_post_update_remove_featured_news() {
  $paragraph_query = \Drupal::entityTypeManager()
    ->getStorage('paragraph')
    ->getQuery();
  $paragraph_query->condition('type', 'featured_news');
  $count = $paragraph_query->count()->execute();
  $paragraph_type_entity = ParagraphsType::load('featured_news');
  if (!$count && $paragraph_type_entity) {
    if ($paragraph_type_entity) {
      \Drupal::entityTypeManager()
        ->getStorage('paragraphs_type')
        ->delete([$paragraph_type_entity]);
    }
  }
}
