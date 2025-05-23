<?php

namespace App\Naming;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\NamerInterface;

class UserBasedNamer implements NamerInterface
{
    public function __construct(private Security $security) {}

    public function name($object, PropertyMapping $mapping): string
    {
        /** @var UploadedFile $file */
        $file = $mapping->getFile($object);
        $ext = $file->guessExtension() ?: 'bin';
        $user = $this->security->getUser();
        $userId = $user ? $user->getId() : 'anon';
        $rand = random_int(1000, 9999);

        return sprintf('%s_%s.%s', $userId, $rand, $ext);
    }
}