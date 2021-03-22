<?php

/**
 * @file
 * Post update functions for tide_landing_page.
 */

use Drupal\paragraphs\Entity\ParagraphsType;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Removes old paragraph types.
 */
function tide_landing_page_post_update_remove_old_paragraph_types() {
  // Makes sure that all cards are deleted.
  $paragraph_storage = \Drupal::entityTypeManager()->getStorage('paragraph');
  $query = \Drupal::entityQuery('paragraph')
    ->condition('parent_type', 'node')
    ->condition('parent_field_name', 'field_landing_page_component')
    ->condition('parent_type', 'node');
  $or = $query->orConditionGroup();
  $or->condition('type', 'card_navigation')
    ->condition('type', 'card_navigation_auto')
    ->condition('type', 'card_navigation_featured')
    ->condition('type', 'card_navigation_featured_auto')
    ->condition('type', 'card_promotion')
    ->condition('type', 'card_promotion_auto')
    ->condition('type', 'card_event')
    ->condition('type', 'card_event_auto');
  $results = $query->condition($or)->execute();
  if (!empty($results)) {
    $paragraph_storage->delete(Paragraph::loadMultiple($results));
  }

  // Deletes cards definitions.
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
