<?php

namespace App\Entity;

interface MediaInterface
{
    public function getType(): ?string;
    public function getUrl(): ?string;
}