<?php
namespace Drupal\cat_facts\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Block of Cat Facts... you can't make this stuff up.
 *
 * @Block(
 *   id = "cat_facts_block",
 *   admin_label = @Translation("Cat Facts")
 * )
 */
class CatFacts extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\cat_facts\CatFactsClient
   */
  protected $catFactsClient;

  /**
   * CatFacts constructor.
   *
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   * @param $cat_facts_client \Drupal\cat_facts\CatFactsClient
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, $cat_facts_client) { 
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->catFactsClient = $cat_facts_client; 
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) { 
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('cat_facts_client')
    );
  }

  
  /**
   * This method will render a render-able array. 
   * The complex contents like forms and views can also be returned.
   * {@inheritdoc}
   */
  public function build() { 
     $cat_facts = $this->catFactsClient->random(10);      

    $items = [];

    foreach ($cat_facts as $cat_fact) {
      $items[] = $cat_fact['text'];
    } 
    
    return [
      '#theme' => 'cat_facts_custom_theme',
      '#items' => $items, 
    ];

  }

}

?>