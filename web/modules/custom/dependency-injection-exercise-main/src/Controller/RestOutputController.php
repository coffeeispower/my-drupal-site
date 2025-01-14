<?php

namespace Drupal\dependency_injection_exercise\Controller;

use Drupal\Core\Controller\ControllerBase;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\dependency_injection_exercise\Services\RandomPhotosService;

/**
 * Provides the rest output.
 */
class RestOutputController extends ControllerBase {

  protected $randomPhotosService;

  public function __construct(RandomPhotosService $random_photos_service) {
    $this->randomPhotosService = $random_photos_service;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('dependency_injection_exercise.random_photos_service')
    );
  }

  /**
   * Displays the photos.
   *
   * @return array[]
   *   A renderable array representing the photos.
   */
  public function showPhotos(): array {
    // Setup build caching.
    $build = [
      '#cache' => [
        'max-age' => 60,
        'contexts' => [
          'url',
        ],
      ],
    ];

    // Try to obtain the photo data via the external API.
    try {
      $data = $this->randomPhotosService->getRandomPhotos();
    }
    catch (GuzzleException $e) {
      $build['error'] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => $this->t('No photos available.'),
      ];
      return $build;
    }

    // Build a listing of photos from the photo data.
    $build['photos'] = array_map(static function ($item) {
      return [
        '#theme' => 'image',
        '#uri' => $item['download_url'],
        // '#alt' => $item['title'],
        // '#title' => $item['title'],
      ];
    }, $data);

    return $build;
  }

}
