<?php

namespace Drupal\tide_landing_page\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the Json Validation constraint.
 */
class JsonValidationConstraintValidator extends ConstraintValidator {

  /**
   * Validator 2.5 and upwards compatible execution context.
   *
   * @var \Symfony\Component\Validator\Context\ExecutionContextInterface
   */
  protected $context;

  /**
   * {@inheritdoc}
   */
  public function validate($entity, Constraint $constraint) {
    if ($entity->hasField('field_paragraph_options')) {
      $this->addConstraint($entity, $constraint, 'field_paragraph_options');
    }

    if ($entity->hasField('field_default_options')) {
      $this->addConstraint($entity, $constraint, 'field_default_options');
    }

    // If the fields do not exist, do not validate anything.
    return NULL;
  }

  /**
   * Helper to add constraint.
   */
  protected function addConstraint($entity, $constraint, $field) {
    $jsonString = $entity->get($field)->getValue();
    $jsonString = (count($jsonString) == 1 ? $jsonString[0]['value'] : FALSE);

    if (strlen($jsonString)) {
      if (!$this->isValidJson($jsonString)) {
        $this->context->buildViolation($constraint->jsonValidationError)
          ->setParameter('%field', $entity->getFieldDefinition($field)->label())
          ->atPath($field)
          ->addViolation();
      }
    }
  }

  /**
   * Helper to check for valid JSON string.
   */
  protected function isValidJson($string) {
    if (is_string($string)) {
      if (is_array(json_decode($string, TRUE)) && (json_last_error() == JSON_ERROR_NONE)) {
        return TRUE;
      }
    }
    return FALSE;
  }

}
