<?php

namespace Drupal\telebot\Form;

use \Drupal\Core\Form\FormBase;
use \Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\RedirectDestination;
use Drupal\Core\Url;
use Drupal\telebot\TelegramBot;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TelebotUserPageForm extends FormBase {

  private $telegram_bot;

  public function getFormId()
  {
    return 'telebot_user_page_form';
  }

  public function __construct(TelegramBot $telegram_bot) {
    $this->telegram_bot = $telegram_bot;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('telebot.bot'),
    );
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {

    $user = User::load(\Drupal::currentUser()->id());
    if(empty($user->get('field_telegram_id'))) {
      $form['telebot_actions']['#type'] = 'container';
      $form['telebot_actions']['login'] = [
        '#type' => 'submit',
        '#value' => 'Login to telegram',
        '#submit' => ["::loginLink"],
      ];
    } else {
      $form['telebot_actions']['disconect'] = [
        '#type' => 'submit',
        '#value' => 'Disconect telegram account',
        '#submit' => ["::disconect"],
      ];
    }


    return $form;
  }

  public function loginLink() {
    $response = new RedirectResponse(Url::fromRoute('telebot.login')->toString());
    $response->send();
  }

  public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state)
  {
  }
}
