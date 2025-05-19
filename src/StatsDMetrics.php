<?php
declare(strict_types=1);

namespace MonkeysLegion\Telemetry;

/**
 * Sends metrics to a local StatsD / Telegraf / DogStatsD agent over UDP.
 */
final class StatsDMetrics implements MetricsInterface
{
    private readonly string $addr;
    private $sock;

    public function __construct(
        string $host = '127.0.0.1',
        int    $port = 8125,
        private readonly string $prefix = 'app'
    ) {
        $this->addr = "udp://{$host}:{$port}";
        $this->sock = @fsockopen($this->addr);
        if (!$this->sock) {
            trigger_error("StatsD socket to {$this->addr} failed – metrics disabled", E_USER_WARNING);
        }
    }

    public function __destruct()
    {
        if (is_resource($this->sock)) {
            fclose($this->sock);
        }
    }

    public function counter(string $name, float $delta = 1, array $labels = []): void
    {
        $this->send("{$this->prefix}.{$name}", "{$delta}|c");
    }

    public function histogram(string $name, float $value, array $labels = []): void
    {
        // Using StatsD “ms” timing; many backends treat it as histogram
        $this->send("{$this->prefix}.{$name}", "{$value}|ms");
    }

    private function send(string $bucket, string $payload): void
    {
        if (is_resource($this->sock)) {
            fwrite($this->sock, "{$bucket}:{$payload}");
        }
    }
}