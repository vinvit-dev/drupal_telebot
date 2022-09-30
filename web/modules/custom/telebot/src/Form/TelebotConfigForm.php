<?php

namespace Drupal\telebot\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\telebot\TelegramBot;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * {@inheritDoc}
 */
class TelebotConfigForm extends ConfigFormBase {

  const SETTINGS = 'telebot.settings';

  private $telegram_bot;

  /**
   * @param \Drupal\telebot\TelegramBot $telegram_bot
   */
  public function __construct(TelegramBot $telegram_bot) {
    $this->telegram_bot = $telegram_bot;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *
   * @return \Drupal\Core\Form\ConfigFormBase|\Drupal\telebot\Form\TelebotConfigForm|static
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('telebot.bot'),
    );
  }

  /**
   * {@inheritDoc}
   */
  protected function getEditableConfigNames() {
    return [static::SETTINGS];
  }

  /**
   * {@inheritDoc}
   */
  public function getFormId() {
    return 'telebot_config_form';
  }

  public function updateBotHook() {
    $this->telegram_bot->updateWebhook();
  }

  public function deleteWebhook() {
    $this->telegram_bot->deleteWebhook();
  }
  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config(static::SETTINGS);

    $form['telebot_actions']['#type'] = 'container';
    $form['telebot_actions']['update_webhook'] = [
      '#type' => 'submit',
      '#value' => 'Update webhook',
      '#submit' => ["::updateBotHook"],
    ];

    $form['telebot_actions']['delete_webhook'] = [
      '#type' => 'submit',
      '#value' => 'Delete webhook',
      '#submit' => ["::deleteWebHook"],
    ];

    $form['welcome_message'] = [
      '#type' => 'textfield',
      '#title' => $this->t("Welcome message"),
      '#description' => $this->t('Display this message on bot login'),
      '#default_value' => $config->get('welcome_message'),
    ];
    $form['bot_api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t("Bot api key"),
      '#default_value' => $config->get('bot_api_key'),
    ];
    $form['bot_user_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t("Bot user name"),
      '#default_value' => $config->get('bot_user_name'),
    ];


    $form['stories'] = [
      '#type' => 'entity_autocomplete',
      '#target_type' => 'user',
      '#title' => $this->t('Stories'),
      '#autocomplete_path' => 'telebot/users-to-admin',
    ];

    $form['bot_admin'] = [
      '#type' => 'textfield',
      '#title' => $this->t("Bot admin user"),
      '#default_value' => $config->get('bot_admin'),
    ];
    $form['db_settings'] = [
      '#type' => 'horizontal_tabs',
    ];

    $form['base'] = [
      '#type' => 'details',
      '#title' => $this->t("Allowed content types"),
      '#group' => 'db_settings',
    ];

    $content_types = \Drupal::entityTypeManager()->getStorage('node_type')->loadMultiple();
    $types = [];
    foreach ($content_types as $ct) {
      $types[$ct->id()] = $this->t($ct->label());
    }

    $form['base']['allowed_content_types'] = [
      '#type' => 'checkboxes',
      '#options' => $types,
      '#default_value' => $config->get('allowed_content_types'),
    ];

    return parent::buildForm($form, $form_state);
  }


  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $bot_user_name = $form_state->getValue('bot_user_name');
    $bot_api_key = $form_state->getValue('bot_api_key');

    $this->config(static::SETTINGS)
      ->set('welcome_message', $form_state->getValue('welcome_message'))
      ->set('bot_api_key', $bot_api_key)
      ->set('bot_user_name', $bot_user_name)
      ->set('bot_admin', $form_state->getValue('bot_admin'))
      ->set('allowed_content_types', $form_state->getValue('allowed_content_types'))
      ->save();
    parent::submitForm($form, $form_state);

    $this->telegram_bot->reInit($bot_api_key, $bot_user_name);
  }

}
