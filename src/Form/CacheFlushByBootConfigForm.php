<?php

namespace Drupal\cache_flush_by_boot\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class CacheFlushByBootConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('cache_flush_by_boot.settings');

    $form['#method'] = 'post';

    $form['cache-flush-by-boot-enabled-checkbox'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Включить очистку'),
      '#description' => $this->t(
          'Установите флажок, если очистка кеша должна происходить при каждой загрузке страниц.'
        )
        . '<br />'
        . $this->t(
          'В некоторых случаях, модули "Internal Page Cache" и "Internal Dynamic Page Cache" должны быть отключены.'
        ),
      '#default_value' => ($config->get('cache_flush_by_boot_enabled') ?? 1),
    ];

    $form['cache-flush-by-boot-full-checkbox'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Очищать кэш полностью'),
      '#description' => $this->t(
          'Установите флажок, если требуется очищать кеш полность при каждой загрузке страниц.'
        )
        . '<br />'
        . $this->t(
          'Если флажок установлен, модули "Internal Page Cache" и "Internal Dynamic Page Cache" могуть оставаться включёнными.'
        ),
      '#default_value' => ($config->get('cache_flush_by_boot_full') ?? 1),
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => 'Сохранить',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cache_flush_by_boot_config_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['cache_flush_by_boot.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    try {
      $cache_flush_by_boot_enabled_value = $form_state->getValue(
        'cache-flush-by-boot-enabled-checkbox'
      );
      $this->config('cache_flush_by_boot.settings')
        ->set('cache_flush_by_boot_enabled', $cache_flush_by_boot_enabled_value)
        ->save();

      $cache_flush_by_boot_full_value = $form_state->getValue(
        'cache-flush-by-boot-full-checkbox'
      );
      $this->config('cache_flush_by_boot.settings')
        ->set('cache_flush_by_boot_full', $cache_flush_by_boot_full_value)
        ->save();

      $status_message = $this->t('Настройки успешно сохранены');
      drupal_set_message($status_message, 'status');
    } catch (Exception $e) {
      $error_message = $e->getMessage();
      drupal_set_message($error_message, 'error');
    }
  }

}
