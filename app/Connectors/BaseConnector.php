<?php

namespace App\Connectors;

use App\Connectors\Contracts\ConnectorContract;
use App\Helpers\LogHelper;
use Throwable;

abstract class BaseConnector implements ConnectorContract
{
    /**
     * The unique identifier for this connector, should
     * map to config key.
     */
    protected string $identifier;

    /**
     * The human readable label for this connector.
     */
    protected string $label;

    /**
     * The description for this connector.
     */
    protected string $description;

    /**
     * The config for this connector.
     */
    protected array $config;

    public function __construct()
    {
        $this->loadConfig();
    }

    /**
     * {@inheritDoc}
     */
    public function loadConfig(): void
    {
        $this->config = config('connectors.'.$this->identifier, []);
    }

    /**
     * {@inheritDoc}
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * {@inheritDoc}
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * {@inheritDoc}
     */
    public function isEnabled(): bool
    {
        return $this->config['enabled'] ?? false;
    }

    /**
     * Log error.
     */
    protected function logError(Throwable $e, ?string $msg = null): void
    {
        LogHelper::error($msg ?? 'Some errors occured while performing this operation.', [
            'connector' => $this->getIdentifier(),
            'error' => $e->getMessage(),
            'code' => $e->getCode() ? $e->getCode() : null,
            'trace' => $e->getTraceAsString(),
        ]);
    }
}
