<?php

namespace Burrow\Broadway;

use Broadway\Domain\DomainEventStreamInterface;

interface DomainEventStreamSerializer
{
    /**
     * @param DomainEventStreamInterface $domainEventStream
     *
     * @return string
     */
    public function serialize(DomainEventStreamInterface $domainEventStream);

    /**
     * @param string $message
     *
     * @return DomainEventStreamInterface
     *
     * @throws \InvalidArgumentException
     */
    public function deserialize($message);
}
