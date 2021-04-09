<?php

declare(strict_types=1);

namespace Storage\MessageHandler;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\Persistence\ManagerRegistry;
use Jmoati\ExifTool\ExifTool;
use League\Flysystem\FilesystemOperator;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Storage\Entity\File;
use Storage\Message\UploadedMessage;
use Storage\Service\FileService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class UploadedHandler implements MessageHandlerInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;
    
    public function __construct(
        private FilesystemOperator $uploadStorage,
        private FilesystemOperator $originalStorage,
        private MessageBusInterface $messageBus,
        private ManagerRegistry $registry,
        private ExifTool $exifTool,
        private FileService $fileService
    ) {
    }
    
    
    public function __invoke(UploadedMessage $message): bool
    {
        $url =  $this->fileService->url('uploaded', $message->path);
        $exif = $this->exifTool->media($url);
        $checksum = hash_file('sha1', $url);
        
        if (!$this->registry->getManager()->isOpen()) {
            $this->registry->resetManager();
        } else {
            $this->registry->getManager()->clear();
        }
        
        $file = (new File())
            ->setExif($exif->data())
            ->setMimetype($exif->mimeType())
            ->setChecksum($checksum);
    
        $this->registry->getManager()->persist($file);
    
        try {
            $this->registry->getManager()->flush();

            $stream = $this->uploadStorage->readStream($message->path);
            $this->originalStorage->writeStream($this->fileService->path($checksum), $stream);
        } catch (UniqueConstraintViolationException $exception) {
            // Do nothing but clean
        } finally {
            $this->uploadStorage->delete($message->path);
        }
        
        return true;
    }
}
