<?php

namespace Storage\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use Storage\Entity\File;
use Storage\Service\FileService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class FileSubscriber implements EventSubscriberInterface
{
    public function __construct(private FileService $fileService)
    {
    }
    
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['getFile', EventPriorities::POST_READ],
        ];
    }
    
    public function getFile(RequestEvent $event): void
    {
        $attributes = $event->getRequest()->attributes;
        
        if (
            'get' === $attributes->get('_api_item_operation_name') &&
            File::class === $attributes->get('_api_resource_class') &&
            null === $attributes->get('_format')
        ) {
            $event->setResponse(new RedirectResponse($this->fileService->url('original', $this->fileService->path($attributes->get('data')->checksum))));
            $event->stopPropagation();
        }
    }
}
