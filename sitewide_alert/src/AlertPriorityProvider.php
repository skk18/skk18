<?php

declare(strict_types=1);

namespace Drupal\sitewide_alert;

use Drupal\Component\Utility\Html;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Provides information on the alert priorities.
 */
class AlertPriorityProvider {

  /**
   * Gets the available alert priorities.
   *
   * @return array
   *   Array of all alert style options.
   */
  public static function alertPriorities(): array {
    $priorities = [];
    $config = \Drupal::config('sitewide_alert.settings');
    if ($alertPrioritiesString = $config->get('alert_priorities')) {
      foreach (explode("\n", strip_tags($alertPrioritiesString)) as $value) {
        if (strpos($value, '|') !== FALSE) {
          [$key, $title] = array_pad(
            array_map('trim', explode('|', $value, 2)),
            2,
            NULL
          );
          $priorities[$key] = $title;
        }
        else {
          $priorities[Html::cleanCssIdentifier($value)] = $value;
        }
      }
    }

    return $priorities;
  }

  /**
   * Given a class get the alert style name.
   *
   * @param string $class
   *   Class name to look up.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   *   Renderable label for class.
   */
  public static function alertPriorityName(string $class): TranslatableMarkup {
    $alertPriority = self::alertPriorities();
    if (isset($alertPriority[$class])) {
      return new TranslatableMarkup($alertPriority[$class]);
    }

    return new TranslatableMarkup('N/A');
  }

}
