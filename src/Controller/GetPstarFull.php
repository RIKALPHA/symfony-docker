<?php

namespace App\Controller;

use App\Repository\PornstarRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetPstarFull extends AbstractController
{
    private PornstarRepository $pornstarRepository;
    private SerializerInterface $serializer;

    public function __construct(
        PornstarRepository $pornstarRepository,
        SerializerInterface $serializer,
        private HttpClientInterface $client,
        private CacheInterface $cache
    )
    {
        $this->pornstarRepository = $pornstarRepository;
        $this->serializer = $serializer;
    }

    public function __invoke(int $id): Response
    {
        $pstar = $this->pornstarRepository->find($id);

        $thumbnails = $pstar->getThumbnails();
        foreach ($thumbnails as &$thumbnail) {
            foreach ($thumbnail['urls'] as $urlKey=>$urlValue) {
                $thumbnail['urls'][$urlKey] = $this->loadAndHandleCache($id, $urlValue);
            }
        }
        $pstar->setThumbnails($thumbnails);

        return new Response($this->serializer->serialize($pstar, 'json'), 200);
    }

    private function loadAndHandleCache(int $id, string $url)
    {
        $value = $this->cache->get($id, function (ItemInterface $item) use ($url): string {
            $item->expiresAfter(3600);
//just a proof of concept :)
var_dump('CACHE_NOT_FOUND');
            $response = $this->client->request(
                'GET',
                $url
            );

            $statusCode = $response->getStatusCode();
            if ($statusCode !== 200) {
                throw new Exception(sprintf('Wrong response Code, got %s', $statusCode));
            }

            //because response is JSON
            return utf8_encode($response->getContent());
        });

        return $value;
    }
}
