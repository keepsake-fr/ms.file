<?php

namespace Storage\Service;


use AsyncAws\S3\Input\GetObjectRequest;
use AsyncAws\S3\S3Client;
use DateTimeImmutable;

class FileService
{
    public function __construct(
        private S3Client $s3Client
    ) {
    }
    
    public function path(string $checksum): string
    {
        return mb_substr($checksum, 0, 3).'/'.mb_substr($checksum, 3, 3).'/'.mb_substr($checksum, 6);
    }
    
    public function url(string $bucket, string $path, string $expiration = "+60 minutes"): string
    {
            $options = [
                'Bucket' => $bucket,
                'Key' => $path,
            ];
        
            
            $command = GetObjectRequest::create($options);
        
            $expiration = new DateTimeImmutable($expiration);
            $request = $this->s3Client->presign($command, $expiration);
        
            return $request;
    }
}
