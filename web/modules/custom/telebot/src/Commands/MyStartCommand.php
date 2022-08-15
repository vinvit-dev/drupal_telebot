<?php

namespace Drupal\telebot\Commands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

/**
 * Start command.
 */
class MyStartCommand extends UserCommand {
  /**
   * @var string
   */
  protected $name = 'start';

  /**
   * @var string
   */
  protected $description = 'Start command';

  /**
   * @var string
   */
  protected $usage = '/start';

  /**
   * @var string
   */
  protected $version = '1.2.0';

  /**
   * Command execute method.
   *
   * @return \Longman\TelegramBot\Entities\ServerResponse
   * @throws \Longman\TelegramBot\Exception\TelegramException
   */
  public function execute(): ServerResponse {
    $config = \Drupal::config('telebot.settings');
    $message = $this->getMessage();
    $chat_id = $message->getChat()->getId();
    // $user_id = $message->getFrom()->getId();
    $result = Request::sendMessage([
      'chat_id' => $chat_id,
      'text' => $config->get('welcome_message'),
    ]);
    return Request::emptyResponse();
  }

}
