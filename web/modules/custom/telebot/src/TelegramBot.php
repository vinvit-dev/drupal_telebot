<?php

namespace Drupal\telebot;

use Longman\TelegramBot\Request;
use Drupal\node\NodeInterface;
use Drupal\telebot\Commands\MyStartCommand;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;

/**
 *
 */
class TelegramBot {

  private $bot_api_key;
  private $bot_username;
  private $mysql_credentials;
  private $hook_url;
  private $telegram;

  /**
   * Telegram bot construct function.
   */
  public function __construct() {
    $config = \Drupal::config('telebot.settings');

    $this->bot_api_key = $config->get('bot_api_key');
    $this->bot_username = $config->get('bot_user_name');
    $this->mysql_credentials = [
      'host'     => $config->get('db_host'),
      'port'     => $config->get('db_port'),
      'user'     => $config->get('db_user_name'),
      'password' => $config->get('db_user_password'),
      'database' => $config->get('db_name'),
    ];

    $this->hook_url = "https://hearing-application-ave-intimate.trycloudflare.com/telebot/hook";

    $this->telegram = new Telegram($this->bot_api_key, $this->bot_username);
    $this->telegram->enableMySql($this->mysql_credentials);
    $this->telegram->addCommandClass(MyStartCommand::class);
  }

  /**
   * @return void
   */
  public function setup() {
    try {
      $result = $this->telegram->setWebhook($this->hook_url);
      if ($result->isOk()) {
        $result->getDescription();
      }
    }
    catch (TelegramException $e) {
      \Drupal::logger('telebot')->error($e->getMessage());
    }
  }

  /**
   * Telegram bot webhook.
   */
  public function webhook() {
    try {
      $this->telegram->handle();
    }
    catch (TelegramException $e) {
      \Drupal::logger('telebot')->error($e->getMessage());
    }
  }

  /**
   *
   */
  public function sendNewNodeMessage(NodeInterface $node) {
    $config = \Drupal::config('telebot.settings');
    $allowed_content_types = $config->get('allowed_content_types');
    if ($allowed_content_types[$node->bundle()] != 0) {
      $title = $node->getTitle();
      $type = $node->getType();
      $result = Request::sendMessage([
        'chat_id' => 740152381,
        'text' => "new node was created \n " . $type . " \n " . $title,
      ]);
    }
  }

}
