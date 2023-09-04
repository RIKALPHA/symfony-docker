<?php

namespace App\Service;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FileDownloader
{
    private const URL = 'https://www.pornhub.com/files/json_feed_pornstars.json';

    public function __construct(
        private LoggerInterface $logger,
        private HttpClientInterface $client,
        private FileData $fileData,
    )
    {
    }

    public function checkAndDownload(): void
    {
        if(!$this->isFileWithTodayStampHere())
        {
            $this->downloadAndSaveToFile();
        }
        gc_collect_cycles();
    }

    private function isFileWithTodayStampHere(): bool
    {
        return $this->fileData->fileExists($this->fileData->getFileName());
    }

    private function downloadAndSaveToFile() {
        try {
            $response = $this->client->request(
                'GET',
                self::URL
            );

            $statusCode = $response->getStatusCode();
            if($statusCode !== 200) {
                throw new Exception(sprintf('Wrong response Code, got %s',$statusCode));
            }

            $fileHandler = fopen($this->fileData->getFileName(), 'w');
            foreach ($this->client->stream($response) as $chunk) {
                fwrite($fileHandler, $chunk->getContent());
            }
            fclose($fileHandler);
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
            throw $exception;
        }
    }
}
