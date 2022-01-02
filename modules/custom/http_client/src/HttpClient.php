<?php
namespace Drupal\http_client;

/**
 * This class returns Http Client
 */

class HttpClient{

    /**
   * @var \GuzzleHttp\Client
   */
  protected $client;
  protected $http_client_factory;
  protected $base_uri;

  /**
   * PostClient constructor.
   *
   * @param $http_client_factory \Drupal\Core\Http\ClientFactory
   */
  public function __construct($http_client_factory, $base_uri) { 
    $this->http_client_factory=$http_client_factory;
    $this->base_uri=$base_uri;
  }

  public function setBaseUri($base_uri){
    $this->base_uri=$base_uri;
  }

  public function getHttpClient(){ 
    $this->client = $this->http_client_factory->fromOptions([
        'base_uri' => $this->base_uri,
      ]);
      return $this->client;
  }

  
}

?>