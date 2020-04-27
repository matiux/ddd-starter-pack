<?php

namespace DDDStarterPack\Infrastructure\Application\Message\SQS;

use ArrayObject;
use Aws\Result;
use DateTimeImmutable;
use DDDStarterPack\Application\Message\Message;
use DDDStarterPack\Application\Message\MessageConsumerConnector;
use DDDStarterPack\Application\Message\MessageProducerResponse;
use Throwable;

class SQSMessageConsumer extends SQSMessageService implements MessageConsumerConnector
{
    private $SQSMessageFactory;
    private $attributeNames = ['ApproximateReceiveCount'];
    private $messageAttributeNames = ['Type', 'OccurredAt'];

    public function __construct(SQSMessageFactory $SQSMessageFactory)
    {
        $this->SQSMessageFactory = $SQSMessageFactory;
    }

    public function consume(): ?Message
    {
        $args = [
            'QueueUrl' => $this->getQueueUrl(),
            'AttributeNames' => $this->attributeNames,
            'MessageAttributeNames' => $this->messageAttributeNames,
        ];

        $response = $this->getClient()->receiveMessage($args);

        return $this->createSqsMessageFromResponse($response);
    }

    private function createSqsMessageFromResponse(Result $response): ?SQSMessage
    {
        $message = $response['Messages'][0] ?? null;

        if (!$message) {
            return null;
        }

        $messageAttributes = $message['MessageAttributes'];

        /** @var SQSMessage $message */
        $message = $this->SQSMessageFactory->build(
            $message['Body'],
            '',
            new DateTimeImmutable($messageAttributes['OccurredAt']['StringValue']),
            $messageAttributes['Type']['StringValue'],
            $message['ReceiptHandle']
        );

        return $message;
    }

    /**
     * @return Message[]
     */
    public function consumeBatch(): array
    {
        // TODO: Implement consumeBatch() method.
    }

    public function delete($messageId): void
    {
        $time = microtime(true);

        while (microtime(true) - $time < 300) {

            try {

                //$result =
                $this->getClient()->deleteMessage([
                    'QueueUrl' => $this->getQueueUrl(),
                    'ReceiptHandle' => $messageId,
                ]);

                break;

            } catch (Throwable $e) {

                sleep(10);
            }
        }

        //return $result;
    }

    public function deleteBatch(ArrayObject $messagesId): void
    {
        // TODO: Implement deleteBatch() method.
    }

    public function sendBatch(array $messages): MessageProducerResponse
    {
        // TODO: Implement sendBatch() method.
    }

    public function getBatchLimit(): int
    {
        // TODO: Implement getBatchLimit() method.
    }
}
