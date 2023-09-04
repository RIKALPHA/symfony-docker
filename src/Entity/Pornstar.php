<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\GetPstarDetails;
use App\Controller\GetPstarList;
use App\Controller\GetPstarFull;
use App\Repository\PornstarRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PornstarRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/view/list',
            controller: GetPstarList::class,
            paginationEnabled: true,
            paginationItemsPerPage: 10,
            paginationMaximumItemsPerPage: 50
        ),
        new Get(
            uriTemplate: '/view/details/{id}',
            controller: GetPstarDetails::class,
            paginationEnabled: false
        ),
        new Get(
            uriTemplate: '/view/full/{id}',
            controller: GetPstarFull::class,
            paginationEnabled: false
        )
    ],
    routePrefix: '/planets',
    normalizationContext: null,
    denormalizationContext: null
)]
class Pornstar
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "NONE")]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $attributes = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $license = null;

    #[ORM\Column(nullable: true)]
    private ?int $wlStatus = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $aliases = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $link = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $thumbnails = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getAttributes(): ?array
    {
        return $this->attributes;
    }

    public function setAttributes(?array $attributes): static
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLicense(): ?string
    {
        return $this->license;
    }

    public function setLicense(?string $license): static
    {
        $this->license = $license;

        return $this;
    }

    public function getWlStatus(): ?int
    {
        return $this->wlStatus;
    }

    public function setWlStatus(?int $wlStatus): static
    {
        $this->wlStatus = $wlStatus;

        return $this;
    }

    public function getAliases(): ?array
    {
        return $this->aliases;
    }

    public function setAliases(?array $aliases): static
    {
        $this->aliases = $aliases;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function getThumbnails(): ?array
    {
        return $this->thumbnails;
    }

    public function setThumbnails(?array $thumbnails): static
    {
        $this->thumbnails = $thumbnails;

        return $this;
    }
}
