<?php

namespace Drupal\cache_flush_by_boot\EventSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Provides a CacheFlushByBootSubscriber.
 */
class CacheFlushByBootSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['cacheFlushByBoot', 20];
    return $events;
  }

  /**
   * // only if KernelEvents::REQUEST !!!
   *
   * @param Symfony\Component\HttpKernel\Event\GetResponseEvent $event
   *   The Event to process.
   *
   * @see Symfony\Component\HttpKernel\KernelEvents for details
   *
   */
  public function cacheFlushByBoot(GetResponseEvent $event) {
    $config = \Drupal::config('cache_flush_by_boot.settings');

    $cache_flush_by_boot_enabled = $config->get(
        'cache_flush_by_boot_enabled'
      ) ?? 1;
    if ($cache_flush_by_boot_enabled) {
      $cache_flush_by_boot_full = $config->get('cache_flush_by_boot_full') ?? 1;
      if ($cache_flush_by_boot_full) {
        drupal_flush_all_caches();
      }
      else {
        drupal_flush_all_caches_cache_flush_by_boot();
      }
    }
  }

}
