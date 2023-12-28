<?php

namespace App;

use Carbon\Carbon;
use Google\Service\Gmail\Message;
use Google\Service\Gmail\MessagePart;
use Google\Service\Gmail\MessagePartHeader;
use Illuminate\Support\Str;
use Spatie\Regex\Regex;

class MailMessage
{
    public function __construct(
        public ?string $from = null,
        public ?string $to = null,
        public ?string $replyTo = null,
        public ?string $subject = null,
        public ?string $bodyHtml = null,
        public ?string $bodyText = null,
        public ?Carbon $receivedAt = null
    ) {

    }

    public static function fromGmailMessage(Message $message): MailMessage
    {
        $headers = $message->getPayload()->getHeaders();

        return new self(
            from: self::extractEmail(self::getHeaderValue($headers, 'From')),
            to: self::extractEmail(self::getHeaderValue($headers, 'To')),
            replyTo: self::extractEmail(self::getHeaderValue($headers, 'Reply-To')),
            subject: self::getHeaderValue($headers, 'Subject'),
            bodyHtml: self::extractBodyHtml($message),
            bodyText: self::extractBodyText($message),
            receivedAt: Carbon::createFromTimestampMs($message->getInternalDate())
        );
    }

    public static function extractEmail(?string $text): ?string
    {
        return rescue(
            callback: fn () => trim(
                Regex::match(
                    pattern: '/([a-z0-9_\.\-])+\@(([a-z0-9\-])+\.)+([a-z0-9]{2,24})+/i',
                    subject: $text
                )->group(0)
            ),
            report: false
        );
    }

    public static function encodeBase64(string $data): string
    {
        return strtr(base64_encode($data), ['+' => '-', '/' => '_']);
    }

    public static function decodeBase64(mixed $data): ?string
    {
        return base64_decode(str_replace(['-', '_'], ['+', '/'], $data));
    }

    protected static function extractBodyHtml(Message $message): ?string
    {
        return self::decodeBase64(
            $message->getPayload()?->getBody()?->getData()
                ?: self::findHtmlPart($message->getPayload()->getParts())?->getBody()?->getData()
        );
    }

    protected static function extractBodyText(Message $message): ?string
    {
        return self::decodeBase64(self::findPlainTextPart($message->getPayload()->getParts())?->getBody()?->getData());
    }

    protected static function findPartByContentType($parts, $contentType)
    {
        if ($parts === null) {
            return null;
        }

        foreach ($parts as $part) {

            if (empty($part->getParts())) {
                $hasContentType = collect($part->getHeaders())
                    ->where('name', 'Content-Type')
                    ->filter(fn ($header) => Str::contains($header->value, $contentType))
                    ->isNotEmpty();

                if ($hasContentType) {
                    return $part;
                }
            } else {
                $foundPart = self::findPartByContentType($part->getParts(), $contentType);
                if ($foundPart !== null) {
                    return $foundPart;
                }
            }
        }

        return null;
    }

    /**
     * @param  MessagePart[]|null  $parts
     */
    protected static function findPlainTextPart(?array $parts)
    {
        return self::findPartByContentType($parts, 'text/plain');
    }

    /**
     * @param  MessagePart[]|null  $parts
     */
    protected static function findHtmlPart(?array $parts)
    {
        return self::findPartByContentType($parts, 'text/html');
    }

    /**
     * @param  MessagePartHeader[]  $headers
     */
    protected static function getHeaderValue(array $headers, string $name)
    {
        return collect($headers)->where('name', $name)->first()?->value;
    }
}
