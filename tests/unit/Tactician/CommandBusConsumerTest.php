<?php
namespace Burrow\tests\Tactician;

use Burrow\Tactician\CommandBusConsumer;
use Burrow\Tactician\CommandDeserializer;
use League\Tactician\CommandBus;
use League\Tactician\Plugins\NamedCommand\NamedCommand;

class CommandBusConsumerTest extends \PHPUnit_Framework_TestCase
{
    private $deserializer;

    private $commandBus;

    protected function tearDown()
    {
        \Mockery::close();
    }

    protected function setUp()
    {
        $this->deserializer = \Mockery::mock(CommandDeserializer::class);
        $this->commandBus = \Mockery::mock(CommandBus::class);
    }

    /**
     * @test
     */
    public function it_should_handle_the_deserialized_command()
    {
        $serializedCommand = json_encode([ 'foo' => 'bar' ]);
        $command = \Mockery::mock(NamedCommand::class);

        $this->deserializer->shouldReceive('deserialize')->with($serializedCommand)->andReturn($command);
        $this->commandBus->shouldReceive('handle')->with($command)->once();

        $consumer = new CommandBusConsumer($this->deserializer, $this->commandBus);
        $consumer->consume($serializedCommand);
    }
}
