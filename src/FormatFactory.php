<?php

declare(strict_types=1);

namespace Onliner\Materialized;

use InvalidArgumentException;

class FormatFactory
{
    /**
     * @param string $class
     * @param array<string, mixed> $config
     *
     * @return Format
     */
    public static function create(string $class, array $config = []): Format
    {
        switch ($class) {
            case Format\Avro::class:
                if (!isset($config['connection']) || !is_string($config['connection'])) {
                    throw new InvalidArgumentException('Materialize AVRO format "connection" config required');
                }

                if (isset($config['options']) || !is_array($config['options'])) {
                    throw new InvalidArgumentException('Materialize AVRO format "options" config must be array');
                }

                /** @phpstan-ignore-next-line */
                return new Format\Avro($config['connection'], $config['options'] ?? []);
            case Format\Bytes::class:
                return new Format\Bytes();
            case Format\Csv::class:
                if (!isset($config['columns']) || !is_int($config['columns'])) {
                    throw new InvalidArgumentException('Materialize CSV format "columns" config required');
                }

                $delimiter = $config['delimiter'] ?? ',';

                if (!is_string($delimiter)) {
                    throw new InvalidArgumentException('Materialize CSV format "delimiter" config must be string');
                }

                return new Format\Csv($config['columns'], $delimiter);
            case Format\Json::class:
                return new Format\Json();
            case Format\Protobuf::class:
                if (!isset($config['connection']) || !is_string($config['connection'])) {
                    throw new InvalidArgumentException('Materialize Protobuf format "connection" config required');
                }

                if (isset($config['options']) || !is_array($config['options'])) {
                    throw new InvalidArgumentException('Materialize Protobuf format "options" config must be array');
                }

                /** @phpstan-ignore-next-line */
                return new Format\Protobuf($config['connection'], $config['options'] ?? []);
            case Format\Text::class:
                return new Format\Text();
            default:
                throw new InvalidArgumentException('Unsupported materialize envelope format');
        }
    }
}
