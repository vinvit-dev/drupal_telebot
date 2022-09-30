<?php

namespace Drupal\telebot\Controller;

use Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\Core\Controller\ControllerBase;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * This is telegram controller.
 */
class TelegramController extends ControllerBase {

  private $bot;

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    $instanse = parent::create($container);
    $instanse->bot = $container->get('telebot.bot');
    return $instanse;
  }

  /**
   * Webhook route.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function webhook(Request $request): Response {
    $this->bot->webhook();

    return new Response();
  }

  /**
   * Login redirect.
   *
   * @return \Drupal\Core\Routing\TrustedRedirectResponse|\Symfony\Component\HttpFoundation\Response
   */
  public function login(): TrustedRedirectResponse|Response {
    $user = User::load(\Drupal::currentUser()->id());
    if ($user != NULL && $user->field_telegram_info == NULL) {
      return new TrustedRedirectResponse($this->bot->generateInviteUrl($user));
    }
    return new TrustedRedirectResponse("https://youtube.com");
  }

  public function userTitle() {
    return \Drupal::currentUser()->getAccountName();
  }
}
