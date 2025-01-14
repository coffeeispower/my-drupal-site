<?php
namespace Drupal\dependency_injection_exercise;
use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Link;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Routing\RouteMatchInterface;
class BreadcrumbsBuilder implements BreadcrumbBuilderInterface {
  use StringTranslationTrait;
  public function applies(RouteMatchInterface $route_match) {
    return $route_match->getRouteObject()->getPath() == "/pictonio/example/photos";
  }
  public function build(RouteMatchInterface $route_match) {

    $breadcrumb = new Breadcrumb();
    $breadcrumb->addCacheContexts(['url.path']);

    $links = [];
    $links[] = Link::createFromRoute($this->t('Home'), '<front>');
    $links[] = Link::createFromRoute("Example", '<none>');
    $links[] = Link::createFromRoute("Pictonio", '<none>');
    $links[] = Link::createFromRoute("Photos", '<none>');

    return $breadcrumb->setLinks($links);
  }
}
