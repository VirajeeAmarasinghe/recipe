<?php

namespace Drupal\manage_inventory\Plugin\Menu\LocalAction;

use Drupal\Core\Menu\LocalActionDefault;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Defines a local action plugin with a dynamic title.
 */
class CustomLocalAction extends LocalActionDefault {

  /**
   * {@inheritdoc}
   */
  public function getTitle() { 
    $title = new TranslatableMarkup("My @arg action", array(
      '@arg' => 'dynamic-title',
    ));
    return $title;
  }

}