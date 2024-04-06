<?php

declare(strict_types=1);

namespace App\Tests\Unit\UseCase\RabbitMQ;

use App\Tests\Unit\UseCase\UseCaseTestCase;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQConsumerTest extends UseCaseTestCase
{
    public function testConsumeMessage()
    {
        // Connect to RabbitMQ
        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        var_dump($channel);

        // Declare the queue
        $channel->queue_declare('my_queue', false, true, false, false);

        // Create a mock message
        $message = [
            'id' => 1,
            'data' => 'Test data',
        ];

        // Convert the message to JSON
        $messageJson = json_encode($message);

        // Publish the message to the queue
        $channel->basic_publish(new AMQPMessage($messageJson), '', 'my_queue');

        // Close the channel and connection
        $channel->close();
        $connection->close();

        // Wait for Symfony to process the message
        sleep(1); // Adjust as needed

        // Verify that Symfony processed the message correctly
        $response = file_get_contents('http://php:8000/check-message');
        $this->assertEquals('Message processed successfully', $response);
    }
}
