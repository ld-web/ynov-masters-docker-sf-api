<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CircProductConfigurationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=CircProductConfigurationRepository::class)
 */
class CircProductConfiguration extends ProductConfiguration
{
  /**
   * @ORM\Column(type="integer")
   */
  private $diameter;

  public function getSurface()
  {
    $radius = $this->diameter / 2;

    return M_PI * ($radius ** 2);
  }

  public function getDiameter(): ?int
  {
    return $this->diameter;
  }

  public function setDiameter(int $diameter): self
  {
    $this->diameter = $diameter;

    return $this;
  }
}
