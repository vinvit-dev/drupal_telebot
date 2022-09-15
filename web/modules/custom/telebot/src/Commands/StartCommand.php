<?php

namespace Drupal\telebot\Commands;

use Drupal\user\Entity\User;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

/**
 * Start command.
 */
class StartCommand extends UserCommand {
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
   *
   * @throws \Longman\TelegramBot\Exception\TelegramException
   */
  public function execute(): ServerResponse {
    $config = \Drupal::config('telebot.settings');
    $message = $this->getMessage();
    $chat_id = $message->getChat()->getId();
    $user_id = $message->getFrom()->getId();
    $text = $message->getText(TRUE);

    $data = [];
    $result = preg_match('/^(?P<token>.+)-(?P<uid>\d+)$/s', $text, $data);

    $user = User::load($data['uid']);
    $datatime = data('m-Y');
    $hash = user_pass_rehash($user, $datatime);

    $result = Request::sendMessage([
      'chat_id' => $chat_id,
      'text' => $config->get('welcome_message'),
    ]);

    return Request::emptyResponse();
  }

}
