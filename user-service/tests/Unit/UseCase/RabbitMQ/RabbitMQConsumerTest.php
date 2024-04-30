<?php

declare(strict_types=1);

namespace App\Tests\Unit\UseCase\RabbitMQ;

use App\Tests\Unit\UseCase\UseCaseTestCase;
use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQConsumerTest extends UseCaseTestCase
{
    private array|string|null $response;

    /**
     * @throws Exception
     */
    public function testConsumeMessage()
    {
        // Connect to RabbitMQ
        $connection = new AMQPStreamConnection(
            host: 'host.docker.internal',
            port: 5672,
            user:
            'guest',
            password:
            'guest',
            keepalive: true
        );
        $channel = $connection->channel();

        /*
         * creates an anonymous exclusive callback queue
         * $callback_queue has a value like amq.gen-_U0kJVm8helFzQk9P0z9gg
         */
        $channel->queue_declare(
            "my_queue",     #queue
            false,  #passive
            true,   #durable
            false,  #exclusive
            false   #auto delete
        );

        $this->response = null;

        // Create a mock message
        $message = 'Hello Testing data';

        $msg = new AMQPMessage(
            $message,
            array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)
        );

        // Publish the message to the queue
        $channel->basic_publish($msg, '', 'my_queue');

        $onResponse = function ($msg) {
            $message = explode(',', $msg->body);
            $this->response = $message;
        };
        $channel->basic_qos(null, 1, null);

        $channel->basic_consume(
            'my_queue',             #queue
            '',             #consumer tag
            false,              #no local
            true,               #no ack
            false,          #exclusive
            false,              #no wait
            $onResponse   #callback
        );

        while (!$this->response) {
            $channel->wait();
        }

        // Close the channel and connection
        $channel->close();
        $connection->close();

        // Wait for Symfony to process the message
        $this->assertEquals($message, $this->response[0]);
    }
}
