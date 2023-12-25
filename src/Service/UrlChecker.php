<?php

declare(strict_types=1);

namespace App\Service;

use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class UrlChecker
{
    public function __construct(private Client $httpClient, private Document $document)
    {
    }

    /**
     * @throws GuzzleException
     * @throws InvalidSelectorException
     */
    public function check(string $url): array
    {
        $response = $this->httpClient->request('GET', $url);
        $data = $this->extractDataFromContent($response->getBody()->getContents());
        return array_merge(
            ['status_code' => $response->getStatusCode()],
            $data
        );
    }

    /**
     * @throws InvalidSelectorException
     */
    private function extractDataFromContent(string $content): array
    {
        $this->document->loadHtml($content);
        $h1 = $this->document->first('h1');
        $title = $this->document->first('title');
        $description = $this->document->first('meta[name="description"]');
        $h1 = ($h1) ? $h1 : '';
        $title = ($title) ? $title : '';
        $description = ($description) ? $description : '';

        return [
            'h1'          => $h1 ? substr(optional($h1)->text(), 0, 255) : '',
            'title'       => $title ? substr(optional($title)->text(), 0, 255) : '',
            'description' => $description ? substr($description->getAttribute('content'), 0, 255) : '',
        ];
    }
}
