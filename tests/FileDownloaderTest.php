<?php

namespace App\Tests;

use App\Service\FileData;
use App\Service\FileDownloader;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FileDownloaderTest extends TestCase
{

    public function testcheckAndDownloadFileHere(): void
    {
        $dummyLogger = $this->createMock(LoggerInterface::class);
        $dummyHttpClient = $this->createMock(HttpClientInterface::class);
        $dummyFileDownloader = $this->createMock(FileData::class);
        $dummyFileDownloader->expects($this->once())->method('fileExists')->willReturn(true);

        $testedService = new FileDownloader($dummyLogger, $dummyHttpClient, $dummyFileDownloader);
        $testedService->checkAndDownload();
    }

    public function testcheckAndDownloadFileNotHere(): void
    {
        $dummyLogger = $this->createMock(LoggerInterface::class);
        $dummyHttpClient = $this->createMock(HttpClientInterface::class);
        $dummyHttpClient->expects($this->once())->method('request')->willReturn(new MockResponse('some body', ['http_code' => 200]));
        $dummyFileDownloader = $this->createMock(FileData::class);
        $dummyFileDownloader->expects($this->once())->method('fileExists')->willReturn(false);
        $dummyFileDownloader->method('getFileName')->willReturn('php://output');

        $testedService = new FileDownloader($dummyLogger, $dummyHttpClient, $dummyFileDownloader);
        $testedService->checkAndDownload();
    }

    public function testcheckAndDownloadFileNotHereException(): void
    {
        $this->expectExceptionMessage('Wrong response Code, got 500');
        $dummyLogger = $this->createMock(LoggerInterface::class);
        $dummyHttpClient = $this->createMock(HttpClientInterface::class);
        $dummyHttpClient->expects($this->once())->method('request')->willReturn(new MockResponse('some body', ['http_code' => 500]));
        $dummyFileDownloader = $this->createMock(FileData::class);
        $dummyFileDownloader->expects($this->once())->method('fileExists')->willReturn(false);
        $dummyFileDownloader->method('getFileName')->willReturn('php://output');

        $testedService = new FileDownloader($dummyLogger, $dummyHttpClient, $dummyFileDownloader);
        $testedService->checkAndDownload();
    }
}
