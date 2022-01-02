<?php
namespace Drupal\cat_facts;
use Drupal\Component\Serialization\Json;

/**
 * This class consumes the API
 */

class CatFactsClient {

    /**
   * @var \GuzzleHttp\Client
   */
  protected $client;

  /**
   * CatFactsClient constructor.
   *
   * @param $http_client_factory \Drupal\Core\Http\ClientFactory
   */
  public function __construct($http_client_factory) { 
    $this->client = $http_client_factory->fromOptions([
      'base_uri' => 'https://cat-fact.herokuapp.com/',
    ]);
  }

  /**
   * Get some random cat facts.
   *
   * @param int $amount
   *
   * @return array
   */
  public function random($amount = 1) {  
    $response = $this->client->get('facts/random', [
      'query' => [
        'amount' => $amount
      ]
    ]); 
    return Json::decode($response->getBody());
    //return (array)json_decode($response->getBody(),true);
  }
}

?>