<?php

namespace Drupal\telebot\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\telebot\TelegramBot;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * This is telegram controller.
 */
class TelegramController extends ControllerBase {

  public function webhook(Request $request) {
    $bot = new TelegramBot();
    $bot->webhook();

    return [
      '#markup' => "Hook page",
    ];
  }

}
