<?php


namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
/**
 * @ODM\Document(collection="logs")
 */
class Logs
{
    /**
     * @ODM\Id()
     */
    private string $id;

    /**
     * @ODM\Field(type="string")
     */
    private ?string $name;

    /**
     * @ODM\Field(type="string")
     */
    private ?string $description;

    /**
     * @ODM\Field(type="date")
     */
    private ?\DateTime $createdAt;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Logs
     */
    public function setId(string $id): Logs
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return Logs
     */
    public function setName(?string $name): Logs
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return Logs
     */
    public function setDescription(?string $description): Logs
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime|null $createdAt
     * @return Logs
     */
    public function setCreatedAt(?\DateTime $createdAt): Logs
    {
        $this->createdAt = $createdAt;
        return $this;
    }

}
