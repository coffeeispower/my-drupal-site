<?php

namespace Drupal\dependency_injection_exercise\Plugin\Block;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\dependency_injection_exercise\Services\RandomPhotosService;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'RestOutputBlock' block.
 *
 * @Block(
 *  id = "rest_output_block",
 *  admin_label = @Translation("Rest output block"),
 * )
 */
class RestOutputBlock extends BlockBase implements ContainerFactoryPluginInterface {

  protected $randomPhotosService;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, RandomPhotosService $random_photos_service) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->randomPhotosService = $random_photos_service;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {

    return new static(
      $configuration, $plugin_id, $plugin_definition,
      $container->get('dependency_injection_exercise.random_photos_service'),
    );
  }
  /**
   * {@inheritdoc}
   */
  public function build(): array {
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
