<?php

namespace Drupal\dependency_injection_exercise\Services;

use GuzzleHttp\ClientInterface;
use Drupal\Component\Serialization\Json;

class RandomPhotosService {
  protected $httpClient;

  public function __construct(ClientInterface $http_client) {
    $this->httpClient = $http_client;
  }

  public function getRandomPhotos() {
    $response = $this->httpClient->request('GET', 'https://picsum.photos/v2/list');
    $raw_data = $response->getBody()->getContents();
    $data = Json::decode($raw_data);
    return $data;
  }
}
