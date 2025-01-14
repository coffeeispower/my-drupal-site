<?php

namespace Drupal\dependency_injection_exercise\Services;

use Drupal\Core\Mail\MailManager;

class MailManagerService extends MailManager {

  /**
   * {@inheritdoc}
   */
  public function mail($module, $key, $to, $langcode, $params = [], $reply = NULL, $send = TRUE) {
    return parent::mail($module, $key, "nope@doesntexist.com", $langcode, $params, $reply, $send);
  }
}
