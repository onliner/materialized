<?php

declare(strict_types=1);

namespace Onliner\Materialized;

use InvalidArgumentException;

class EnvelopeFactory
{
    /**
     * @param string $type
     * @param string $format
     * @param array<string, mixed> $config
     *
     * @return Envelope
     */
    public static function create(string $type, string $format, array $config = []): Envelope
    {
        $format = FormatFactory::create($format, $config);

        switch ($type) {
            case Envelope::NONE:
                return Envelope::none($format);
            case Envelope::UPSERT:
                return Envelope::upsert($format);
            case Envelope::DEBEZIUM:
                return Envelope::debezium($format);
            default:
                throw new InvalidArgumentException('Unsupported materialize envelope type');
        }
    }
}
