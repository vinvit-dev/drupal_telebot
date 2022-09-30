<?php

namespace Drupal\telebot\Form;

use Drupal\Core\Entity\EntityStorageException;
use \Drupal\Core\Form\FormBase;
use \Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\RedirectDestination;
use Drupal\Core\Url;
use Drupal\telebot\TelegramBot;
use Drupal\user\Entity\User;
use functional\Append;
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
    $this->current_user = User::load(\Drupal::currentUser()->id());
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('telebot.bot'),
    );
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {
    if(empty($this->current_user->get('field_telegram_id')->getValue())) {
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

  /**
   * @return void
   */
  public function loginLink() {
    $response = new RedirectResponse(Url::fromRoute('telebot.login')->toString());
    $response->send();
  }

  public function disconect() {
    if(!empty($this->current_user->get('field_telegram_id'))) {
      $this->current_user->set('field_telegram_id', "");
      $this->current_user->save();
    } else {
      \Drupal::messenger()->addStatus("You don`t have connected account");
    }
  }

  /**
   * {@inerhitDoc}
   */
  public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state)
  {
  }
}
