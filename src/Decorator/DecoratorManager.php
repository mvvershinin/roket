<?php

namespace src\Decorator;

use DateTime;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use src\Integration\DataProvider;
use src\Dtos\InputDto;
use src\Dtos\ResponseDto;
use Throwable;

/**
 * @property string $host
 * @property string $user
 * @property string $password
 */
final class DecoratorManager extends DataProvider
{
    public const EXPIRED_PERIOD = '+1 day';

    /**
     * @param string $host
     * @param string $user
     * @param string $password
     * @param CacheItemPoolInterface $cache
     * @param LoggerInterface $logger
     */
    public function __construct(
        protected string $host,
        protected string $user,
        protected string $password,
        protected CacheItemPoolInterface $cache,
        protected LoggerInterface $logger
    )
    {
        parent::__construct($host, $user, $password);
    }

    /**
     * @param InputDto $input
     * @return array
     */
    public function getResponse(InputDto $input): ?ResponseDto
    {
        try {
            $cacheItem = $this->cache->getItem(
                $input->getCacheKey()
            );
            if ($cacheItem->isHit()) {
                // Должен вернуть ResponseDto
                return $cacheItem->get();
            }

            $result = $this->get($input);

            $cacheItem
                ->set($result)
                ->expiresAt(
                    (new DateTime())->modify(self::EXPIRED_PERIOD)
                );

            return $result;
        } catch (Throwable $e) {
            $this->logger->critical('Error', $e);
        }

        return null;
    }
}
