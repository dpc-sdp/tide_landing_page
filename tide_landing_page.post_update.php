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

  // Currently, we don't delete card_promotion and card_promotion_auto.
  $config = \Drupal::configFactory()
    ->getEditable('field.field.node.landing_page.field_landing_page_component');
  $settings = $config->get('settings');
  if (isset($settings['handler_settings']['target_bundles']['card_promotion'])) {
    unset($settings['handler_settings']['target_bundles']['card_promotion']);
  }
  if (isset($settings['handler_settings']['target_bundles']['card_promotion_auto'])) {
    unset($settings['handler_settings']['target_bundles']['card_promotion_auto']);
  }
  if (isset($settings['handler_settings']['target_bundles_drag_drop']['card_promotion']['enabled'])) {
    if ($settings['handler_settings']['target_bundles_drag_drop']['card_promotion']['enabled'] == TRUE) {
      $settings['handler_settings']['target_bundles_drag_drop']['card_promotion']['enabled'] = FALSE;
    }
  }
  if (isset($settings['handler_settings']['target_bundles_drag_drop']['card_promotion_auto']['enabled'])) {
    if ($settings['handler_settings']['target_bundles_drag_drop']['card_promotion_auto']['enabled'] == TRUE) {
      $settings['handler_settings']['target_bundles_drag_drop']['card_promotion_auto']['enabled'] = FALSE;
    }
  }
  $config->set('settings', $settings)->save();
}
