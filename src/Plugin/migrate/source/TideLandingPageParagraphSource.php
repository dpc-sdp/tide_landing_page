<?php

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;
use Drupal\migrate\Plugin\Exception\BadPluginDefinitionException;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Source plugin for retrieving paragraph values.
 *
 * @MigrateSource(
 *   id = "tide_landing_page_paragraph_source"
 * )
 *
 *
 * Example usage in migrate config file:
 *
 * @code
 *
 * source:
 *   plugin: tide_landing_page_paragraph_source
 *   paragraph_bundle: card_navigation
 *   node_target_field: field_landing_page_component
 *   paragraph_field_names:
 *     - field_name_1
 *     - field_name_2
 *     - field_name_3
 *
 * @endcode
 */
class TideLandingPageParagraphSource extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function fields() {
    return [
      'id' => $this->t('Paragraph ID'),
      'revision_id' => $this->t('Paragraph revision ID'),
      'type' => $this->t('Paragraph bundle'),
      'parent_id' => $this->t('Node ID'),
      'parent_field_name' => $this->t('Node field name'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'id' => [
        'type' => 'integer',
        'alias' => 'p',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    if (!isset($this->configuration['paragraph_bundle'])) {
      throw new BadPluginDefinitionException($this->pluginDefinition['source']['plugin'], 'paragraph_bundle');
    }
    if (!isset($this->configuration['node_target_field'])) {
      throw new BadPluginDefinitionException($this->pluginDefinition['source']['plugin'], 'field_landing_page_component');
    }
    $fields = [
      'id',
      'revision_id',
      'type',
      'parent_id',
      'parent_field_name',
    ];
    return $this->select('paragraphs_item_field_data', 'p')
      ->fields('p', $fields)
      ->condition('parent_field_name', $this->configuration['node_target_field'])
      ->condition('type', $this->configuration['paragraph_bundle']);
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    if (!isset($this->configuration['paragraph_field_names'])) {
      throw new BadPluginDefinitionException($this->pluginDefinition['source']['plugin'], 'paragraph_field_names');
    }

    // Sets node nid.
    $parent_id = $row->getSourceProperty('parent_id');
    $row->setSourceProperty('nid', $parent_id);

    // Gets current Paragraph entity.
    $paragraph_id = $row->getSourceProperty('id');
    $paragraph = Paragraph::load($paragraph_id);
    // Loop fields.
    foreach ($this->configuration['paragraph_field_names'] as $field_name) {
      $value = $paragraph->get($field_name)->getValue();
      $value = reset($value);
      $row->setSourceProperty('tide_paragraph_source_' . $field_name, $value);
    }
    return parent::prepareRow($row);
  }

}
