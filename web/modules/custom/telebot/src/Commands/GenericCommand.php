<?php

namespace Drupal\telebot\Commands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;

/**
 * Generic command.
 *
 * Gets executed for generic commands, when no other appropriate one is found.
 */
class GenericCommand extends SystemCommand {
  /**
   * {@inheritdoc}
   */
  protected $name = 'generic';

  /**
   * {@inheritdoc}
   * @throws \Longman\TelegramBot\Exception\TelegramException
   */
  public function execute(): ServerResponse {
    $message = $this->getMessage();
    $command = $message->getCommand();

    return $this->replyToChat("Command /${command} not found.");
  }

}
