<?php
use Doctrine\ORM\Mapping as ORM;
namespace App\Entity\Traits;

trait Timestampable {

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function updateTimestamps()
    {
        if($this->getCreatedAt()=== null) {
            $this->setCreatedAt(new \DateTimeImmutable);
        } else {
            $this->setUpdatedAt(new \DateTimeImmutable);
        }
    }
}