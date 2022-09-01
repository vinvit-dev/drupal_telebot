<?php

namespace Drupal\telebot\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
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

    $response = [
      "message" => "Login is successful",
      "status" => 200,
    ];

    return JsonResponse::create($response, 200);
  }

  /**
   *
   */
  public function hook() {
    $bot = new TelegramBot();
    $bot->webhook();

    $response = [
      "message" => "Login is successful",
      "status" => 200,
    ];

    return JsonResponse::create($response, 200);
  }

  /**
   *
   */
  public function deleteHook() {
    $bot = new TelegramBot();
    $bot->delete_webhook();

    $response = [
      "message" => "Hook was canceled",
      "status" => 200,
    ];

    return JsonResponse::create($response, 200);
  }

}
