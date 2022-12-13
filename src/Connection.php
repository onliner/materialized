<?php

declare(strict_types=1);

namespace Onliner\Materialized;

use BadMethodCallException;
use PDO;
use PDOStatement;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Connection
{
    private const DSN_TEMPLATE = 'pgsql:host=%s;port=%d;dbname=%s';
    private const DEFAULTS = [
        'host' => 'localhost',
        'port' => 6875,
        'user' => 'materialize',
        'pass' => null,
        'path' => 'materialize',
    ];

    /**
     * @var PDO
     */
    private $connection;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param PDO $connection
     * @param LoggerInterface $logger
     */
    private function __construct(PDO $connection, LoggerInterface $logger)
    {
        $this->connection = $connection;
        $this->logger = $logger;
    }

    /**
     * @param string $url
     * @param LoggerInterface|null $logger
     *
     * @return self
     */
    public static function open(string $url, LoggerInterface $logger = null): self
    {
        $parts = array_replace(self::DEFAULTS, array_filter(parse_url($url) ?: []));
        $dsn = sprintf(self::DSN_TEMPLATE, $parts['host'], $parts['port'], trim((string) $parts['path'], '/'));

        return new self(new PDO($dsn, (string) $parts['user'], (string) $parts['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]), $logger ?? new NullLogger());
    }

    /**
     * @param string $query
     * @param array<string> $params
     * @param int $mode
     *
     * @return PDOStatement
     */
    public function fetch(string $query, array $params = [], int $mode = PDO::ATTR_DEFAULT_FETCH_MODE): PDOStatement
    {
        $query = vsprintf($query, $params);

        $this->logger->debug(sprintf('Materialize query: %s', $query));

        if (!$statement = $this->connection->query($query, $mode)) {
            throw new BadMethodCallException();
        }

        return $statement;
    }

    /**
     * @param string|Command $command
     * @param array<string> $params
     *
     * @return void
     */
    public function execute($command, array $params = []): void
    {
        if ($command instanceof Command) {
            $command = (string) $command;
        }

        $command = vsprintf($command, $params);

        $this->logger->debug(sprintf('Materialize statement: %s', $command));
        $this->connection->exec($command);
    }
}
