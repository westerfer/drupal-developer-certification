<?php

/**
 * @file
 * Generates markup to be display. Functionality in this controller is
 * wired to Drupal in mymodule.routing.yml.
 */

namespace Drupal\mymodule\Controller;

use Drupal\Core\Controller\ControllerBase;

class FirstController extends ControllerBase {

  public function simpleContent(): array {
    return [
      '#type' => 'markup',
      '#markup' => t('Hello Drupal World.'),
    ];
  }

  public function variableContent($name_1, $name_2): array {
    return [
      '#type' => 'markup',
      '#markup' => t('@name1 and @name2 say hello to you!',
      ['@name1' => $name_1, '@name2' => $name_2]),
    ];
  }
}
