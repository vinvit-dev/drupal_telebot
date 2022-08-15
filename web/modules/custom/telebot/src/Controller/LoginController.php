<?php

namespace Drupal\telebot\Controller;

use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;
use Drupal\Core\Controller\ControllerBase;

/**
 * This is login telebot controler.
 */
class LoginController extends ControllerBase {

  private $bot_api_key  = '5593871762:AAHFPm5Nrus0iF6X8LXGqRWnvoAqPBYJemI';
  private $bot_username = 'VinvitBot';


  private $mysql_credentials = [
    'host'     => 'db',
  // Optional.
    'port'     => 3306,
    'user'     => 'root',
    'password' => 'root',
    'database' => 'telegram',
  ];

  /**
   * This is login function.
   *
   * @throws \Longman\TelegramBot\Exception\TelegramException
   */
  public function login() {
    try {
      // Create Telegram API object.
      $telegram = new Telegram($this->bot_api_key, $this->bot_username);

      // Enable MySQL.
      $telegram->enableMySql($this->mysql_credentials);

      // Handle telegram getUpdates request.
      $telegram->handleGetUpdates();

      var_dump($telegram);
    }
    catch (TelegramException $e) {
      // Log telegram errors
      // echo $e->getMessage();
    }

    return [
      '#markup' => "The is hello world text",
    ];
  }

}
