<?php
namespace Burrow\Broadway;

use Broadway\Domain\DomainMessage;

interface DomainMessageSerializer
{
    /**
     * @param DomainMessage $object
     * @return array
     */
    public function serialize(DomainMessage $object);

    /**
     * @param array $serializedObject
     *
     * @return DomainMessage
     */
    public function deserialize(array $serializedObject);
}
