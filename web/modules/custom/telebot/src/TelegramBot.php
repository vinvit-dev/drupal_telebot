<?php

namespace Drupal\telebot;

use Drupal\Core\Url;
use Drupal\Core\Site\Settings;
use Drupal\telebot\Commands\MyGenericCommand;
use Drupal\telebot\Commands\NewNodeMessageCommand;
use Longman\TelegramBot\Request;
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
    $this->mysql_credentials = Settings::get('telebot_mysql_credentials');

    $this->hook_url = 'https://' . \Drupal::request()->getHttpHost() . '/telebot/hook';

    $bot_admin = $config->get('bot_admin');

    $this->telegram = new Telegram($this->bot_api_key, $this->bot_username);
    $this->telegram->enableMySql($this->mysql_credentials);

    if($bot_admin != NULL) {
      $this->telegram->enableAdmin($bot_admin);
    }

    $this->telegram->addCommandsPath(\Drupal::service('extension.list.module')->getPath('telebot') . '/src/Commands');
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
   * Delete telegram bot webhook.
   */
  public function delete_webhook() {
    try {
      $result = $this->telegram->deleteWebhook();
      echo $result->getDescription();
    }
    catch (TelegramException $e) {
      \Drupal::logger('telebot')->error($e->getMessage());
    }
  }

  /**
   *
   */
  public function sendNewNodeMessage(\Drupal\node\NodeInterface $node) {
    $config = \Drupal::config('telebot.settings');
    $allowed_content_types = $config->get('allowed_content_types');
    if ($allowed_content_types[$node->bundle()] != 0) {
      $title = $node->getTitle();

      $body = $node->get('body')->getValue()[0]['value'];
      $body = strip_tags($body);
      $body = substr($body, 0, 100);

      $text = "New node was created";
      $text .= "\nTitle: " . $title;
      $body ? $text .= "\nText: " . $body : "\n";
      $text .= "\nLink: " . Url::fromRoute('entity.node.canonical', ['node' => 0], ['absolute' => TRUE])->toString();

      $result = Request::sendMessage([
        'chat_id' => 740152381,
        'parse_mode' => 'html',
        'text' => $text,
      ]);
    }
  }

}
