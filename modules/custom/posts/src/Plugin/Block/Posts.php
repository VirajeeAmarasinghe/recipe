<?php
namespace Drupal\posts\Plugin\Block;

use Drupal\Core\Block\BlockBase;

use Drupal\Component\Serialization\Json;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\http_client\HttpClient;
use Drupal\pagination\Pagination;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Block of Posts
 *
 * @Block(
 *   id = "posts_block",
 *   admin_label = @Translation("Posts")
 * )
 */
class Posts extends BlockBase implements ContainerFactoryPluginInterface{

  /**
   * @var \Drupal\http_client\HttpClient
   * @var \Drupal\pagination\Pagination
   */
  protected $postClient;
  protected $pagination;

  /**
   * Post constructor.
   *
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   * @param Drupal\http_client\HttpClient $http_custom_client 
   * @param Drupal\pagination\Pagination $pagination
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, HttpClient $http_custom_client, Pagination $pagination) { 
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    
    $this->pagination=$pagination;

    $http_custom_client->setBaseUri('https://api.instantwebtools.net/v1/'); 
    $this->postClient=$http_custom_client->getHttpClient();  
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) { 
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('http_custom_client'),
      $container->get('pagination')
    );
  }  

  public function getPosts() {  
    $response = $this->postClient->get('passenger'); 
    $responseArray=Json::decode($response->getBody())['data'];
   
    $page = $this->pagination->getPage(count($responseArray), 10);    

    $response = $this->postClient->get('passenger', [
        'query' => [
          'page' => $page,
          'size' => 10
    ]]);
    
    return Json::decode($response->getBody())['data'];
}  

  
  /**
   * This method will render a render-able array. 
   * The complex contents like forms and views can also be returned.
   * {@inheritdoc}
   */
  public function build() { 
    $posts = $this->getPosts();      

    $items = [];

    $header = [
      '#' => '#',
      'ID' => t('ID'),
      'Name' => t('Name'),
      'Trips' => t('Trips'),
    ];

    foreach ($posts as $index=>$post) { 
      $items[$post['_id']] = array(
        '#' => $index+1,
        'ID' => $post['_id'],
        'Name' => $post['name'],
        'Trips' => $post['trips']        
      );
    } 
 
            
    // $form['table'] = [
    //   '#type' => 'tableselect',
    //   '#header' => $header,
    //   '#options' => $items,
    //   '#empty' => $this->t('No posts found'),
    // ];

    $form['table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $items,
      '#empty' => $this->t('No posts found'),
    ];

    // Finally add the pager.
    $form['pager'] = array(
      '#type' => 'pager'
    );
    
    return $form;

  }

}

?>