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

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config(static::SETTINGS)
      ->set('welcome_message', $form_state->getValue('welcome_message'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
