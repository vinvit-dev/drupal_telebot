<?php

namespace Drupal\telebot\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * {@inheritDoc}
 */
class TelebotConfigForm extends ConfigFormBase {

  const SETTINGS = 'telebot.settings';

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

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config(static::SETTINGS);

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
    $this->config(static::SETTINGS)
      ->set('welcome_message', $form_state->getValue('welcome_message'))
      ->set('bot_api_key', $form_state->getValue('bot_api_key'))
      ->set('bot_user_name', $form_state->getValue('bot_user_name'))
      ->set('bot_admin', $form_state->getValue('bot_admin'))
      ->set('allowed_content_types', $form_state->getValue('allowed_content_types'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
