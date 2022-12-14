<?php

namespace Drupal\telebot\Commands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;

/**
 * Generic command.
 *
 * Gets executed for generic commands, when no other appropriate one is found.
 */
class GenericMessageCommand extends SystemCommand {
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

    return $this->replyToChat("Are you stupid? You can't just input some text! Input some command");
  }

}
