<?php

namespace App\Controller;

use App\Repository\PornstarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class GetPstarDetails extends AbstractController
{
    private PornstarRepository $pornstarRepository;
    private SerializerInterface $serializer;

    public function __construct(PornstarRepository $pornstarRepository, SerializerInterface $serializer)
    {
        $this->pornstarRepository = $pornstarRepository;
        $this->serializer = $serializer;
    }

    public function __invoke($id): Response
    {
        $pstar = $this->pornstarRepository->find($id);

        return new Response($this->serializer->serialize($pstar, 'json'),200);
    }
}
