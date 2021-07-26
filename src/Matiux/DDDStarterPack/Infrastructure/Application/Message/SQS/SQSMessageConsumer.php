<?php

declare(strict_types=1);

namespace DDDStarterPack\Infrastructure\Application\Message\SQS;

use ArrayObject;
use Aws\Result;
use DateTimeImmutable;
use DDDStarterPack\Application\Message\Message;
use DDDStarterPack\Application\Message\MessageConsumerConnector;
use DDDStarterPack\Application\Message\MessageProducerResponse;
use Throwable;
use Webmozart\Assert\Assert;

class SQSMessageConsumer extends SQSMessageService implements MessageConsumerConnector
{
    /** @var SQSMessageFactory */
    private $SQSMessageFactory;

    /** @var string[] */
    private $attributeNames = ['ApproximateReceiveCount'];

    /** @var string[] */
    private $messageAttributeNames = ['Type', 'OccurredAt'];

    public function __construct(SQSMessageFactory $SQSMessageFactory)
    {
        parent::__construct();

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

        return $this->extractSqsMessageFromResponse($response);
    }

    /**
     * @psalm-suppress InvalidReturnType
     *
     * @return Message[]
     */
    public function consumeBatch(): array
    {
        // TODO: Implement consumeBatch() method.
    }

    /**
     * @param string $messageId
     */
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

    /**
     * @psalm-suppress InvalidReturnType
     *
     * @param array $messages
     *
     * @return MessageProducerResponse
     */
    public function sendBatch(array $messages): MessageProducerResponse
    {
        // TODO: Implement sendBatch() method.
    }

    /**
     * @psalm-suppress InvalidReturnType
     *
     * @return int
     */
    public function getBatchLimit(): int
    {
        // TODO: Implement getBatchLimit() method.
    }

    private function extractSqsMessageFromResponse(Result $response): ?SQSMessage
    {
        if (empty($response['Messages'])) {
            return null;
        }

        Assert::isArray($response['Messages']);
        Assert::count($response['Messages'], 1);

        $message = $response['Messages'][0];

        Assert::isArray($message);
        Assert::string($message['Body']);
        Assert::string($message['ReceiptHandle']);

        /** @var array{OccurredAt: array{StringValue: string}, Type: array{StringValue: string}} $messageAttributes */
        $messageAttributes = $message['MessageAttributes'];

        return $this->SQSMessageFactory->build(
            $message['Body'],
            '',
            new DateTimeImmutable($messageAttributes['OccurredAt']['StringValue']),
            $messageAttributes['Type']['StringValue'] ?? null,
            $message['ReceiptHandle']
        );
    }
}
