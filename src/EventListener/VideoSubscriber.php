<?php
namespace App\EventListener;

use App\Entity\Video;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class VideoSubscriber implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->processVideo($args);
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $this->processVideo($args);
    }

    private function processVideo(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof Video) {
            return;
        }

        dd('VideoSubscriber triggered', $entity->getUrl()); // <- doit s'afficher
    }
}
