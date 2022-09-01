<?php

namespace Drupal\telebot\Commands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;

/**
 * Generic command.
 *
 * Gets executed for generic commands, when no other appropriate one is found.
 */
class MyGenericCommand extends SystemCommand {
  /**
   * {@inheritdoc}
   */
  protected $name = 'mygenericmessage';

  /**
   * {@inheritdoc}
   */
  public function execute(): ServerResponse {
    $message = $this->getMessage();

    $result = Request::sendMessage([
      'text' => "Error: No command found :(",
    ]);
    return Request::emptyResponse();
  }

}
