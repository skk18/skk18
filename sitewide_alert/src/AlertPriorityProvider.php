<?php

declare(strict_types=1);

namespace Drupal\sitewide_alert;

use Drupal\Component\Utility\Html;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Provides information on the alert priority.
 */
class AlertPriorityProvider {

  /**
   * Gets the available alert priority.
   *
   * @return array
   *   Array of all alert priority options.
   */
  public static function alertPriority(): array {
    $priority = [];
    $config = \Drupal::config('sitewide_priority.settings');
    if ($alertPriorityString = $config->get('alert_priority')) {
		
      foreach (explode("\n", strip_tags($alertPriorityString)) as $value) {
        if (strpos($value, '|') !== FALSE) {
          [$key, $title] = array_pad(
            array_map('trim', explode('|', $value, 2)),
            2,
            NULL
          );
          $priority[$key] = $title;
        }
        else {
          $priority[Html::cleanCssIdentifier($value)] = $value;
        }
      }
    }

    return $priority;
  }

  /**
   * Given a class get the alert priority name.
   *
   * @param string $class
   *   Class name to look up.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   *   Renderable label for class.
   */
  public static function alertPriorityName(string $class): TranslatableMarkup {
    $alertPriorities = self::alertPriority();
	
    if (isset($alertPriorities[$class])) {
      return new TranslatableMarkup($alertPriorities[$class]);
    }

    return new TranslatableMarkup('N/A');
  }

}
