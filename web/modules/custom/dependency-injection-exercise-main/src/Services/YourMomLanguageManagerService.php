<?php

namespace Drupal\dependency_injection_exercise\Services;

use Drupal\Core\Language\LanguageManager;

class YourMomLanguageManagerService extends LanguageManager {
  public function getLanguageName($langcode): string {
    return "YOUR MOM";
  }
}
