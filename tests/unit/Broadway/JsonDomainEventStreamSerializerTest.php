<?php

namespace Burrow\tests\Console;

use Broadway\Domain\DateTime;
use Broadway\Domain\DomainEventStream;
use Broadway\Domain\DomainMessage;
use Broadway\Domain\Metadata;
use Burrow\Broadway\DomainMessageSerializer;
use Burrow\Broadway\JsonDomainEventStreamSerializer;

class JsonDomainEventStreamSerializerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DomainMessageSerializer
     */
    private $serializer;

    protected function tearDown()
    {
        \Mockery::close();
    }

    protected function setUp()
    {
        $this->serializer = \Mockery::mock(DomainMessageSerializer::class);
    }

    /**
     * @test
     */
    public function it_should_serialize_as_an_array_of_domain_messages()
    {
        $event = new DomainMessage('a', 0, new Metadata(), [], DateTime::now());
        $stream = new DomainEventStream([
            $event
        ]);

        $this->serializer->shouldReceive('serialize')
            ->with($event)
            ->andReturn(['serialized']);

        $serializer = new JsonDomainEventStreamSerializer($this->serializer);
        $serialized = $serializer->serialize($stream);

        $this->assertEquals('[["serialized"]]', $serialized);
    }

    /**
     * @test
     */
    public function it_should_deserialize_an_array_of_domain_messages_and_return_a_stream()
    {
        $event = new DomainMessage('a', 0, new Metadata(), [], DateTime::now());
        $stream = new DomainEventStream([
            $event
        ]);

        $this->serializer->shouldReceive('deserialize')
            ->with(['serialized'])
            ->andReturn($event);

        $serializer = new JsonDomainEventStreamSerializer($this->serializer);
        $deserialized = $serializer->deserialize('[["serialized"]]');

        $this->assertEquals($stream, $deserialized);
    }

    /**
     * @test
     * @expectedException \Burrow\Serializer\DeserializeException
     * @dataProvider getMalformedDomainMessagesExample
     */
    public function it_fails_to_deserialize_an_array_of_domain_messages_from_a_malformed_message($serialized)
    {
        $serializer = new JsonDomainEventStreamSerializer($this->serializer);
        $serializer->deserialize($serialized);
    }

    public function getMalformedDomainMessagesExample()
    {
        return [
            [
                '"example"'
            ],
            [
                '['
            ],
        ];
    }
}
