<?php

namespace App\Service;

use App\Entity\Pornstar;
use App\Repository\PornstarRepository;
use Doctrine\ORM\EntityManagerInterface;

class FileProcessor
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PornstarRepository $pornstarRepository,
        private FileData $fileData
    )
    {
    }

    public function getDataNumbers(): int
    {
        $itemsCount = 0;
        $handle = fopen($this->fileData->getFileName(), "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $line = json_decode('{' . trim($line, ',') . '}', true);
                if (isset($line['itemsCount'])) {
                    $itemsCount = $line['itemsCount'];
                }
                unset($line);
            }
            fclose($handle);
        }
        return $itemsCount;
    }

    public function handleOne($data): void
    {
        $pstar = $this->pornstarRepository->find($data['id']);
        if(null === $pstar) {
            $pstar = new Pornstar();
        }
        $pstar->setAttributes($data['attributes']);
        $pstar->setId($data['id']);
        $pstar->setName($data['name']);
        $pstar->setLicense($data['license']);
        $pstar->setWlStatus($data['wlStatus']);
        $pstar->setAliases($data['aliases']);
        $pstar->setLink($data['link']);
        $pstar->setThumbnails($data['thumbnails']);
        $this->entityManager->persist($pstar);
        unset($pstar);
    }

    public function flushChunk(): void
    {
        $this->entityManager->flush();
    }

}
