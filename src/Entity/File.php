<?php

namespace Storage\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Storage\Action\UploadAction;
use Storage\Entity\Trait\Uuidable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @Gedmo\SoftDeleteable(timeAware=true)
 * @ApiResource(
 *     collectionOperations={
 *         "post"={
 *             "controller"=UploadAction::class,
 *             "deserialize"=false,
 *             "openapi_context"={
 *                 "requestBody"={
 *                     "content"={
 *                         "application/octet-stream"={
 *                             "schema"={
 *                                 "type"="string",
 *                                 "format"="binary"
 *                             }
 *                         }
 *                     }
 *                 }
 *             }
 *         },
 *         "get"
 *     },
 *     itemOperations={
 *         "get",
 *         "delete"
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"checksum"="exact"})
 */
class File
{
    use Uuidable;
    use TimestampableEntity;
    use SoftDeleteableEntity;
    
    /**
     * @ORM\Column(length=40, unique=true, options={"fixed": true})
     * @Assert\Length(min="40", max="40")
     * @Assert\Unique()
     */
    public string $checksum;
    
    /**
     * @ORM\Column()
     */
    public string $mimetype;
    
    /**
     * @ORM\Column(type="json")
     */
    public array $exif;
    
    public function setChecksum(string $checksum): self
    {
        $this->checksum = $checksum;
        
        return $this;
    }
    
    public function setMimetype(string $mimetype): self
    {
        $this->mimetype = $mimetype;
    
        return $this;
    }
    
    public function setExif(array $exif): self
    {
        $this->exif = $exif;
        
        return $this;
    }
}
