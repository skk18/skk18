<?php

namespace Drupal\sitewide_alert\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the LimitToPages constraint.
 */
class LimitToPagesConstraintValidator extends ConstraintValidator {

  /**
   * Validator 2.5 and upwards compatible execution context.
   *
   * @var \Symfony\Component\Validator\Context\ExecutionContextInterface
   */
  protected $context;

  /**
   * {@inheritdoc}
   */
  public function validate($entity, Constraint $constraint): void {
    $value = $entity->get('limit_to_pages')->value;

    if (!empty($value) && $this->validPathsValue($value) === FALSE) {
      $this->context->buildViolation($constraint->messageInvalidPaths)
        ->atPath('limit_to_pages')
        ->addViolation();
    }
  }

  /**
   * Returns TRUE if all paths given are valid. False if any paths are invalid.
   *
   * @param $pagesValue
   *
   * @return bool
   */
  private function validPathsValue($pagesValue): bool {
    foreach (explode("\n", strip_tags($pagesValue)) as $path) {
      $path = trim($path);

      if (!empty($path) && !str_starts_with($path, '/')) {
        return FALSE;
      }
    }

    return TRUE;
  }

}
