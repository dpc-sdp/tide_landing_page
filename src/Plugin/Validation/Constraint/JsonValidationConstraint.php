<?php

namespace Drupal\tide_landing_page\Plugin\Validation\Constraint;

use Drupal\Core\Entity\Plugin\Validation\Constraint\CompositeConstraintBase;

/**
 * Verify that the JSON string is valid.
 *
 * @Constraint(
 *   id = "JsonValidation",
 *   label = @Translation("Json Validation", context = "Validation"),
 *   type = "string"
 * )
 */
class JsonValidationConstraint extends CompositeConstraintBase {

  /**
   * Error message for invalid JSON string.
   *
   * @var string
   */
  public $jsonValidationError = 'The %field field should contain a valid JSON string.';

  /**
   * {@inheritdoc}
   */
  public function coversFields() {
    return ['field_paragraph_options', 'field_default_options'];
  }

}
