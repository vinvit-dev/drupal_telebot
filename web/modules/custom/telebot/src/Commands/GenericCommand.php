<?php

namespace Drupal\telebot\Commands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

/**
 * Generic command.
 *
 * Gets executed for generic commands, when no other appropriate one is found.
 */
class GenericCommand extends SystemCommand {
  /**
   * {@inheritdoc}
   */
  protected $name = 'genericmessage';

  /**
   * {@inheritdoc}
   * @throws \Longman\TelegramBot\Exception\TelegramException
   */
  public function execute(): ServerResponse {
    $message = $this->getMessage();

    return $this->replyToChat("Command not found... ");
  }

}
