<?php

namespace Drupal\telebot\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides "Telegram Block".
 *
 * @Block(
 *   id = "telegram_block",
 *   admin_label = @Translation("Telegram block"),
 * )
 */
class TelegramInfoBlock extends BlockBase {

  /**
   * {@inheritDoc}
   */
  public function build() {
    return [
      '#markup' => '<a href="/telebot/login">Login to telegram</a>',
    ];
  }

}
