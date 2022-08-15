<?php

namespace Drupal\telebot\Controller;

use Drupal\telebot\TelegramBot;
use Drupal\Core\Controller\ControllerBase;

/**
 * This is telegram controller.
 */
class TelegramController extends ControllerBase {


  /**
   * This is login function.
   *
   * @throws \Longman\TelegramBot\Exception\TelegramException
   */
  public function login() {
    $bot = new TelegramBot();
    $bot->setup();

    return [
      '#markup' => "This is login page",
    ];
  }

  public function hook() {
    $bot = new TelegramBot();
    $bot->webhook();

    return [
      '#markup' => "This is hook page",
    ];
  }

}
