<?php
namespace Drupal\drupalup_json_api\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\drupalup_json_api\DrupalUpJsonApiClient;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\http_client\HttpClient;


/**
 * Json api controller class
 */
class JsonApiController extends ControllerBase {

    /**
     * @var \Drupal\drupalup_json_api\DrupalUpJsonApiClient
     */
    protected $drupalUpJsonAPiClient;
    
    public function __construct(HttpClient $http_custom_client){
        $http_custom_client->setBaseUri('https://jsonplaceholder.typicode.com/'); 
        $this->drupalUpJsonAPiClient=$http_custom_client->getHttpClient(); 
    }

    /**
    * {@inheritdoc}
   */
    public static function create(ContainerInterface $container) { 
        //calls the constructor
        //The new static part says: create a new instance of JsonApiController and returns
        return new static(
            $container->get('http_custom_client'),
        );
      }
    
      /**
       * @return JsonResponse
       */
    public function renderApi() {
        $photos=$this->getResults();

        return new JsonResponse(
            [
                'data' => $photos,
                'method' => 'GET'
            ]
        );
    }

    private function getResults(){
        $photos = $this->getPhotos();

        return $photos;
    }

    public function getPhotos() {  
        $response = $this->drupalUpJsonAPiClient->get('photos'); 
    
        return (array)json_decode($response->getBody(),true);    
      }
}
?>