<?php

namespace App\Entity;

interface MediaInterface
{
    public function getId(): ?int;
    public function getUrl(): ?string;
    public function setUrl(?string $url): static;
    public function getCreatedAt(): ?\DateTimeImmutable;
    public function setCreatedAt(\DateTimeImmutable $createdAt): static;
    public function getTrick(): ?Trick;
    public function setTrick(?Trick $trick): static;
}