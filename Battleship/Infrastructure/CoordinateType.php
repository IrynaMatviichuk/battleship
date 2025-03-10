<?php

namespace Battleship\Infrastructure;

use Battleship\Domain\Coordinate;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class CoordinateType extends Type
{
    const MYTYPE = 'coordinate';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return 'COORDINATE';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Coordinate
    {
        list($row, $column) = sscanf($value, 'COORDINATE(%f %f)');

        return new Coordinate($row, $column);

    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if ($value instanceof Coordinate) {
            $value = sprintf('COORDINATE(%F %F)', $value->getRow(), $value->getColumn());
        }

        return $value;
    }

    public function getName(): string
    {
        return self::MYTYPE;
    }
}
