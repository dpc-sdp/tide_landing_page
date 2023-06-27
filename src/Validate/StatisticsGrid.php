<?php

namespace Drupal\tide_landing_page\Validate;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Validation handler for statistics grid component.
 */
class StatisticsGrid {

  /**
   * Validates given element.
   *
   * @param array $element
   *   The paragraph element to process.
   * @param \Drupal\Core\Form\FormStateInterface $formState
   *   The form state.
   * @param array $form
   *   The complete form structure.
   */
  public static function validate(array &$element, FormStateInterface $formState, array &$form) {
    $deltas = $element['subform']['field_statistic_block']['widget'];
    
    $count = 0;
    foreach (array_keys($deltas) as $key) {
      if (is_numeric($key)) {
        $count += 1;
      }
    }

    $error = FALSE;
    if ($count < 2) {
      $error = TRUE;
    }
    if ($error) {
      $formState->setError(
        $element,
        new TranslatableMarkup('The statistics grid component need minimum two blocks to be added.')
      );
    }
  }

}
