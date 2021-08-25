<?php

namespace App\Tests\Functional;

use PHPUnit\Framework\Assert;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

trait ViolationAssertTrait
{
    protected function assertPropertyIsInvalid(string $propertyPath, string $expectedErrorMessage, ConstraintViolationListInterface $violations): void
    {
        $actualErrorMessage = self::findViolationMessagesByProperty($propertyPath, $violations);

        Assert::assertContains(
            $expectedErrorMessage,
            $actualErrorMessage,
            sprintf(
                'Searching the error message after validation for the "%s in [%s]"',
                $propertyPath,
                implode(', ', $actualErrorMessage),
            ),
        );
    }

    protected function assertPropertyIsValid(string $propertyPath, ConstraintViolationListInterface $violations): void
    {
        $actualErrorMessage = self::findViolationMessagesByProperty($propertyPath, $violations);

        Assert::assertEmpty($actualErrorMessage);
    }

    /**
     * @return string[]
     */
    private static function findViolationMessagesByProperty(string $propertyPath, ConstraintViolationListInterface $violations): array
    {
        $violationMessages = [];

        foreach ($violations as $violation) {
            assert($violation instanceof ConstraintViolationInterface);

            if ($violation->getPropertyPath() === $propertyPath) {
                $violationMessages[] = $violation->getMessage();
            }
        }

        return $violationMessages;
    }
}
