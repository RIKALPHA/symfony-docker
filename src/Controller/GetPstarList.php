<?php

namespace App\Controller;

use App\Repository\PornstarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class GetPstarList extends AbstractController
{
    private PornstarRepository $pornstarRepository;
    private SerializerInterface $serializer;

    public function __construct(PornstarRepository $pornstarRepository, SerializerInterface $serializer)
    {
        $this->pornstarRepository = $pornstarRepository;
        $this->serializer = $serializer;
    }

    public function __invoke(Request $request): Response
    {
        $page = (int) $request->query->get('page', 1);

        $pstars = $this->pornstarRepository->paginated($page);

        return new Response($this->serializer->serialize($pstars, 'json'),200);
    }
}
