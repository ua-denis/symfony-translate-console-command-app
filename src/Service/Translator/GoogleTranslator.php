<?php

namespace App\Service\Translator;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GoogleTranslator implements TranslatorInterface
{
    private HttpClientInterface $client;
    private string $apiKey;

    public function __construct(HttpClientInterface $client, string $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    public function translate(string $text, string $targetLang): string
    {
        try {
            $response = $this->client->request('POST', 'https://translation.googleapis.com/language/translate/v2', [
                'json' => [
                    'q' => $text,
                    'target' => $targetLang,
                    'source' => 'auto',
                    'key' => $this->apiKey,
                ],
            ]);

            $data = $response->toArray();

            if (isset($data['data']['translations'][0]['translatedText'])) {
                return $data['data']['translations'][0]['translatedText'];
            }

            throw new \RuntimeException('Translation failed: no translated text found in response.');
        } catch (TransportExceptionInterface|ClientExceptionInterface|ServerExceptionInterface|RedirectionExceptionInterface $e) {
            throw new \RuntimeException('Translation failed: '.$e->getMessage());
        } catch (DecodingExceptionInterface $e) {
            throw new \RuntimeException('Decoding response to array failed: '.$e->getMessage());
        }
    }
}