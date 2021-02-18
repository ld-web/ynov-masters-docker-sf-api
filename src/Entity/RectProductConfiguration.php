<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RectProductConfigurationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=RectProductConfigurationRepository::class)
 */
class RectProductConfiguration extends ProductConfiguration
{
  /**
   * @ORM\Column(type="integer")
   */
  private $width;

  /**
   * @ORM\Column(type="integer")
   */
  private $height;

  /**
   * @ORM\Column(type="integer")
   */
  private $thickness;

  public function getSurface(): int
  {
    return $this->width * $this->height;
  }

  public function getWidth(): ?int
  {
    return $this->width;
  }

  public function setWidth(int $width): self
  {
    $this->width = $width;

    return $this;
  }

  public function getHeight(): ?int
  {
    return $this->height;
  }

  public function setHeight(int $height): self
  {
    $this->height = $height;

    return $this;
  }

  public function getThickness(): ?int
  {
    return $this->thickness;
  }

  public function setThickness(int $thickness): self
  {
    $this->thickness = $thickness;

    return $this;
  }
}
