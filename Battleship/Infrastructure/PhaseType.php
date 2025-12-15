<?php

namespace Battleship\Infrastructure;

use Battleship\Domain\Phase;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class PhaseType extends Type
{
    const MYTYPE = 'phase';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return 'PHASE';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Coordinate
    {
        return Phase::from($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if ($value instanceof Phase) {
            $value = $value->value;
        }

        return $value;
    }

    public function getName(): string
    {
        return self::MYTYPE;
    }
}
