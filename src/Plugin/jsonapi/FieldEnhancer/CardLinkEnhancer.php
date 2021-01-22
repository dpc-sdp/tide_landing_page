<?php

namespace Drupal\tide_landing_page\Plugin\jsonapi\FieldEnhancer;

use Drupal\tide_media\Plugin\jsonapi\FieldEnhancer\ImageEnhancer;
use Drupal\jsonapi_extras\Plugin\ResourceFieldEnhancerBase;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\taxonomy\Entity\Term;
use Drupal\media\Entity\Media;
use Drupal\file\Entity\File;
use Shaper\Util\Context;

/**
 * Adds necessary fields from internal link.
 *
 * @ResourceFieldEnhancer(
 *   id = "card_link_enhancer",
 *   label = @Translation("Card Link Enhancer(Adds necessary fields from internal link)"),
 *   description = @Translation("Adds necessary fields from internal link.")
 * )
 */
class CardLinkEnhancer extends ResourceFieldEnhancerBase {

  /**
   * {@inheritdoc}
   */
  protected function doUndoTransform($data, Context $context) {
    if (!empty($data) && (strpos($data['uri'], 'entity:node/') !== FALSE)) {
      $nid = str_replace("entity:node/", "", $data['uri']);
      if (!empty($nid)) {
        $data['url'] = $this->getAlias($nid);
        $data['image'] = $this->getImage($nid);
        $data['internal_node_fields'] = $this->getCardFields($nid);
      }
    }
    return $data;
  }

  /**
   * {@inheritdoc}
   */
  protected function doTransform($data, Context $context) {
    return $data;
  }

  /**
   * {@inheritdoc}
   */
  public function getOutputJsonSchema() {
    return [
      'anyOf' => [
        ['type' => 'object'],
      ],
    ];
  }

  /**
   * Helper function to get alias for entity node.
   *
   * @param string $nid
   *   The node id.
   *
   * @return string
   *   The alias for node.
   */
  public function getAlias($nid) {
    $url = '';
    if (!empty($nid)) {
      $aliasByPath = \Drupal::service('path.alias_manager')->getAliasByPath('/node/' . $nid);
      $url = $this->getPathAliasWithoutSitePrefix(['alias' => $aliasByPath]);
    }
    return $url;
  }

  /**
   * Helper function to get all the necessary node fields.
   *
   * @param string $nid
   *   The node id.
   *
   * @return array
   *   The array of fields value.
   */
  public function getCardFields($nid) {
    $card_fields = [];
    $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
    // Add title from the node.
    $node_title = $node->get('title')->getValue();
    $card_fields['title'] = $node_title ? $node_title[0]['value'] : '';
    // Add content type label from the node.
    $node_type = $node->type->entity->label();
    $card_fields['node_type'] = $node_type ? $node_type : '';
    // Add topic from the node.
    $topic_id = $node->hasField('field_topic') ? $node->get('field_topic')->getValue()[0]['target_id'] : '';
    if ($topic_id) {
      $card_fields['topic'] = Term::load($topic_id)->get('name')->value;
    }
    // Add tags from the node.
    $tag_ids = $node->hasField('field_tags') ? $node->get('field_tags')->getValue() : '';
    if ($tag_ids) {
      foreach ($tag_ids as $id) {
        $card_fields['tags'][] = Term::load($id['target_id'])->get('name')->value;
      }
    }
    // Add summary from the node.
    $summary = $node->hasField('field_landing_page_summary') ? $node->get('field_landing_page_summary')->getValue() : '';
    $card_fields['summary'] = $summary ? $summary[0]['value'] : '';
    // Add feature image from the node.
    $feature_image = $node->hasField('field_featured_image') ? $node->get('field_featured_image')->getValue() : '';
    $module_handler = \Drupal::moduleHandler();
    if ($module_handler->moduleExists('tide_news')) {
      // Add the summary field for news.
      if ($node->hasField('body')) {
        $news_summary = $node->get('body')->getValue() ? $node->get('body')->getValue()[0]['summary'] : '';
        // If no news summary, will check for landing page summary.
        $card_fields['summary'] = $news_summary ? $news_summary : $card_fields['summary'];
      }
      // Add the date field for news.
      if ($node->hasField('field_news_date')) {
        $card_fields['date'] = $node->get('field_news_date')->getValue()[0];
      }
    }
    if ($module_handler->moduleExists('tide_event')) {
      // Add event fields.
      if ($node->hasField('field_event_details')) {
        $paragraph_id = $node->get('field_event_details')->getValue()[0]['target_id'];
        $paragraph = $paragraph_id ? Paragraph::load($paragraph_id) : '';
        if ($paragraph && $paragraph->field_paragraph_date_range->getValue()) {
          $event_date = $paragraph->field_paragraph_date_range->getValue()[0];
          $card_fields['date'] = $event_date ? $event_date : '';
        }
        $location = $paragraph->field_paragraph_location->getValue() ? $paragraph->field_paragraph_location->getValue()[0]['locality'] : '';
        if ($location) {
          $card_fields['location'] = $location;
        }
        $event_author = $node->hasField('field_node_author') ? $node->get('field_node_author')->getValue() : '';
        $card_fields['event_status'] = $event_author ? $event_author[0]['value'] : '';
        $event_status = $node->hasField('field_status') ? $node->get('field_status')->getValue() : '';
        $card_fields['event_status'] = $event_status ? $event_status[0]['value'] : '';
      }
    }
    if ($module_handler->moduleExists('tide_grant')) {
      // Add the date field for grants.
      if ($node->hasField('field_node_dates')) {
        $card_fields['date'] = $node->get('field_node_dates')->getValue()[0];
      }
      // Add the ongoing field for grants.
      if ($node->hasField('field_node_on_going')) {
        $card_fields['ongoing'] = $node->get('field_node_on_going')->getValue()[0]['value'];
      }
    }
    if ($module_handler->moduleExists('tide_publication')) {
      // Add the authors field for publication.
      $author_ids = $node->hasField('field_publication_authors') ? $node->get('field_publication_authors')->getValue() : '';
      if ($author_ids) {
        foreach ($author_ids as $id) {
          $card_fields['publication_authors'][] = Term::load($id['target_id'])->get('name')->value;
        }
      }
    }
    return $card_fields;
  }

  /**
   * Helper function to add image field details.
   *
   * @param string $nid
   *   The node id.
   *
   * @return array
   *   The image data with focal point values.
   */
  public function getImage($nid) {
    $image = [];
    $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
    if ($node->get('field_featured_image')->getValue()) {
      $image_id = $node->get('field_featured_image')->getValue()[0]['target_id'];
      $media = $image_id ? Media::load($image_id) : '';
      // Get the ID of the media object image field.
      $media_field = $media ? $media->get('field_media_image')->getValue() : '';
      if ($media_field) {
        // Image Details.
        $image['data'] = $media_field;
        $file = $media_field[0]['target_id'] ? File::load($media_field[0]['target_id']) : '';
        if ($file) {
          // Get URL of the image file.
          $image['url'] = $file->url();
          // Get image crop values.
          $crop = !empty($file) ? ImageEnhancer::getCropEntity($file, 'focal_point') : '';
          $focal_point = !empty($crop) ? $crop->position() : '';
          $image['meta']['focal_point'] = $focal_point;
        }
      }
    }
    return $image;
  }

  /**
   * Extract the original alias without site prefix.
   *
   * @param array $path
   *   The path.
   * @param string $site_base_url
   *   The site base URL if the path alias is an absolute URL.
   *
   * @return string
   *   The raw internal alias without site prefix.
   */
  public function getPathAliasWithoutSitePrefix(array $path, $site_base_url = '') {
    $pattern = '/^\/site\-(\d+)\//';
    if ($site_base_url) {
      $pattern = '/' . preg_quote($site_base_url, '/') . '\/site\-(\d+)\//';
    }
    return preg_replace($pattern, $site_base_url . '/', $path['alias']);
  }

}
