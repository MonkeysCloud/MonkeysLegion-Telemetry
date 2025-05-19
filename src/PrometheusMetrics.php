<?php
declare(strict_types=1);

namespace MonkeysLegion\Telemetry;

use Prometheus\CollectorRegistry;
use Prometheus\Storage\Adapter;

/**
 * Thin adapter around promphp/prometheus_client.
 *
 * $adapter can be any storage supported by promphp:
 *   new Prometheus\Storage\Redis([...])
 *   new Prometheus\Storage\APC()
 */
final class PrometheusMetrics implements MetricsInterface
{
    private CollectorRegistry $registry;

    public function __construct(Adapter $adapter)
    {
        // default namespace “app”
        $this->registry = CollectorRegistry::getDefault($adapter);
    }

    public function counter(string $name, float $delta = 1, array $labels = []): void
    {
        $c = $this->registry->getOrRegisterCounter('app', $name, '', array_keys($labels));
        $c->inc(array_values($labels), $delta);
    }

    public function histogram(string $name, float $value, array $labels = []): void
    {
        $h = $this->registry->getOrRegisterHistogram('app', $name, '', array_keys($labels));
        $h->observe($value, array_values($labels));
    }
}