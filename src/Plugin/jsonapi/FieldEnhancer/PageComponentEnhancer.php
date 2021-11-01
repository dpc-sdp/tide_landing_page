<?php

namespace Drupal\vicgovau_core\Plugin\jsonapi\FieldEnhancer;

use Drupal\jsonapi_extras\Plugin\ResourceFieldEnhancerBase;
use Shaper\Util\Context;
use Drupal\taxonomy\Entity\Term;

/**
 * Perform additional manipulations to page component fields.
 *
 * Page Component Enhancer is used to to fetch the field value details of
 * the taxonomy referenced in the custom component paragraph and
 * add those fields key value pair to the current API json output.
 *
 * @ResourceFieldEnhancer(
 *   id = "page_components_enhancer",
 *   label = @Translation("Page Component Enhancer (includes Overwrite for paragraphs)"),
 *   description = @Translation("Includes Additional Custom Details")
 * )
 */
class PageComponentEnhancer extends ResourceFieldEnhancerBase {

  /**
   * {@inheritdoc}
   */
  public function doUndoTransform($data, Context $context) {
    // Custom components paragraph enhancer.
    if (!empty($data) && !empty($data['type']) && $data['type'] == 'paragraph--custom_component') {
      $paragraph = \Drupal::service('entity.repository')->loadEntityByUuid('paragraph', $data['id']);
      if (!$paragraph->get('field_paragraph_custom_component')->isEmpty()) {
        $termId = $paragraph->get('field_paragraph_custom_component')->target_id;
        $term = Term::load($termId);
        $field_machine_name = $term->get('field_machine_name')->value;
        $field_paragraph_custom_component = "";
        if (!$paragraph->get('field_paragraph_options')->isEmpty()) {
          $field_paragraph_custom_component = $paragraph->get('field_paragraph_options')->value;
        }
        elseif (!$term->get('field_default_options')->isEmpty()) {
          $field_paragraph_custom_component = $term->get('field_default_options')->value;
        }
        $data['meta']['machine_name'] = $field_machine_name;
        $data['meta']['custom_component'] = $field_paragraph_custom_component;
      }
    }
    return $data;
  }

  /**
   * {@inheritdoc}
   */
  public function doTransform($data, Context $context) {
    if (isset($data['meta']['machine_name'])) {
      unset($data['meta']['machine_name']);
    }
    if (isset($data['meta']['custom_component'])) {
      unset($data['meta']['custom_component']);
    }
    return $data;
  }

  /**
   * {@inheritdoc}
   */
  public function getOutputJsonSchema() {
    return [
      'anyOf' => [
        ['type' => 'array'],
        ['type' => 'null'],
        ['type' => 'object'],
      ],
    ];
  }

}
