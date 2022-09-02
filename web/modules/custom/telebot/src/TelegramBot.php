<?php

namespace Drupal\telebot;

use Drupal\node\NodeInterface;
use Drupal\Core\Url;
use Drupal\Core\Site\Settings;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;

use Drupal\telebot\Commands\StartCommand;
use Drupal\telebot\Commands\GenericCommand;

/**
 * Main telegram bot class.
 */
class TelegramBot {

  private $bot_api_key;
  private $bot_username;
  private $mysql_credentials;
  private $hook_url;
  private $telegram;

  protected $commands_list = [
    StartCommand::class,
    GenericCommand::class,
  ];

  /**
   * Telegram bot construct function.
   */
  public function __construct() {
    $config = \Drupal::config('telebot.settings');

    $this->bot_api_key = $config->get('bot_api_key');
    $this->bot_username = $config->get('bot_user_name');
    $this->mysql_credentials = Settings::get('telebot_mysql_credentials');

    $this->hook_url = 'https://' . \Drupal::request()->getHttpHost() . '/telebot/hook';

    $this->telegram = new Telegram($this->bot_api_key, $this->bot_username);
    $this->telegram->enableMySql($this->mysql_credentials);

    $bot_admin = $config->get('bot_admin');
    if ($bot_admin != NULL) {
      $this->telegram->enableAdmin($bot_admin);
    }

    $this->telegram->addCommandClasses($this->commands_list);
  }

  public function reInit() {
    $this->telegram = new Telegram($this->bot_api_key, $this->bot_username);
  }

  public function refreshWebhook() {
    try {
      $this->telegram->setWebhook($this->hook_url);
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

}
