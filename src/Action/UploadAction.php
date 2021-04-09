<?php

namespace Storage\Action;

use Jmoati\MetronomeBundle\Server\StreamedRequest;
use League\Flysystem\FilesystemOperator;
use Storage\Message\UploadedMessage;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

class UploadAction
{
    public function __construct(
        private FilesystemOperator $uploadStorage,
        private MessageBusInterface $messageBus,
    ) {}
    
    public function __invoke(StreamedRequest $request)
    {
        $uuid = uuid_create();
        $stream = $request->getContent(true);
        
        /** Not optimal */
        $this->uploadStorage->writeStream($uuid, $stream);
  
        $this->messageBus->dispatch(new UploadedMessage($uuid));
        
        return new Response('', Response::HTTP_CREATED);
    }
}
