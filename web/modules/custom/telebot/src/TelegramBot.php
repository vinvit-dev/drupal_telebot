<?php

namespace Drupal\telebot;

use Drupal\Core\Site\Settings;
use Drupal\user\UserInterface;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;

use Drupal\telebot\Commands\StartCommand;
use Drupal\telebot\Commands\GenericCommand;
use Drupal\telebot\Commands\GenericMessageCommand;

/**
 * Main telegram bot class.
 */
class TelegramBot {

  private $bot_api_key;
  private $bot_username;
  private $mysql_credentials;
  private $hook_url;
  public $telegram;

  protected $commands_list = [
    StartCommand::class,
    GenericCommand::class,
    GenericMessageCommand::class,
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

  /**
   *
   */
  public function reInit($bot_api_key, $bot_username) {
    $this->telegram = new Telegram($bot_api_key, $bot_username);
    \Drupal::messenger()->addMessage($this->bot_username . " settings was updated");
  }

  /**
   *
   */
  public function updateWebhook() {
    try {
      $this->telegram->setWebhook($this->hook_url);
      \Drupal::messenger()->addMessage("Webhook was updated");
    }
    catch (TelegramException $e) {
      \Drupal::messenger()->addError("Error when update webhook. Look at logs");
      \Drupal::logger('telebot')->error($e->getMessage());
    }
  }

  /**
   * Telegram bot webhook.
   */
  public function webhook() {
    try {
      $result = $this->telegram->handle();
    }
    catch (TelegramException $e) {
      \Drupal::logger('telebot')->error($e->getMessage());
    }
  }

  /**
   * Delete telegram bot webhook.
   */
  public function deleteWebhook() {
    try {
      $this->telegram->deleteWebhook();
      \Drupal::messenger()->addMessage("Webhook was deleted");
    }
    catch (TelegramException $e) {
      \Drupal::messenger()->addError("Error when delete webhook");
      \Drupal::logger('telebot')->error($e->getMessage());
    }
  }

  /**
   *
   */
  public function generateInviteUrl(UserInterface $user) {
    $datatime = date('m-Y');
    return "https://t.me/" . $this->bot_username . "?start=" . user_pass_rehash($user, $datatime) . "-" . $user->id();
  }

}
