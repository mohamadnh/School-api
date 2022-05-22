<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\EmptyController;
use App\Repository\StudentRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
/**
 * @Vich\Uploadable()
 */
#[ApiResource(
    collectionOperations: [
        'Get',
        'Post' => [
            "security" => "is_granted('ROLE_ADMIN')",
            'openapi_context' => [
                'requestBody' => [
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'firstname' => [
                                        'type' => 'string',
                                    ],
                                    'lastname' => [
                                        'type' => 'string',
                                    ],
                                    'dateOfBirth' => [
                                        'type' => 'string',
                                    ],
                                    'classe' => [
                                        'type' => Classe::class,
                                    ],
                                    'file' => [
                                        'type' => 'string',
                                        'format' => 'binary'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],
    itemOperations: [
        'delete' => [
            "security" => "is_granted('ROLE_ADMIN')",
        ],
        'get',
        'update' => [
            "security" => "is_granted('ROLE_ADMIN')",
            'path' => '/students/{id}/update',
            'method' => 'POST',
            'controller' => EmptyController::class,
            'openapi_context' => [
                'requestBody' => [
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'firstname' => [
                                        'type' => 'string',
                                    ],
                                    'lastname' => [
                                        'type' => 'string',
                                    ],
                                    'dateOfBirth' => [
                                        'type' => 'string',
                                    ],
                                    'classe' => [
                                        'type' => Classe::class,
                                    ],
                                    'file' => [
                                        'type' => 'string',
                                        'format' => 'binary'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],
    denormalizationContext: [
        'groups' => ['write:Student']
    ],
    normalizationContext: [
        'groups' => ['read:collection'],
        'openapi_definition_name' => 'Collection'
    ],
    paginationClientItemsPerPage: true,
    paginationItemsPerPage: 2,
    paginationMaximumItemsPerPage: 2,
    security: 'is_granted("ROLE_USER")'
),
    ApiFilter(SearchFilter::class, properties: ['firstname' => 'partial','lastname' => 'partial','classe.name' => 'partial'])]
class Student
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['read:collection', 'write:Student'])]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read:collection', 'write:Student'])]
    private ?string $firstname;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read:collection', 'write:Student'])]
    private ?string $lastname;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['read:collection', 'write:Student'])]
    private ?DateTimeInterface $dateOfBirth;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $imagePath;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="students", fileNameProperty="imagePath")
     */
    #[Groups(['write:Student'])]
    private ?File $file;

    #[Groups('read:collection')]
    private ?string $fileUrl;

    #[ORM\ManyToOne(targetEntity: Classe::class, cascade: ['remove'], inversedBy: 'students')]
    #[Groups(['read:collection', 'write:Student'])]
    private ?Classe $classe;

    #[ORM\Column(type: 'datetime')]
    private ?DateTimeInterface $updatedAt;

    #[ORM\OneToMany(mappedBy: 'student', targetEntity: StudentsGrades::class)]
    private $studentsGrades;

    public function __construct()
    {
        $this->studentsGrades = new ArrayCollection();
        $this->updatedAt = new DateTime();
    }

    /**
     * @return string|null
     */
    public function getFileUrl(): ?string
    {
        return $this->fileUrl;
    }

    /**
     * @param string|null $fileUrl
     */
    public function setFileUrl(?string $fileUrl): void
    {
        $this->fileUrl = $fileUrl;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getDateOfBirth(): ?DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(DateTimeInterface $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getClasse(): ?Classe
    {
        return $this->classe;
    }

    public function setClasse(?Classe $classe): self
    {
        $this->classe = $classe;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, StudentsGrades>
     */
    public function getStudentsGrades(): Collection
    {
        return $this->studentsGrades;
    }

    public function addStudentsGrade(StudentsGrades $studentsGrade): self
    {
        if (!$this->studentsGrades->contains($studentsGrade)) {
            $this->studentsGrades[] = $studentsGrade;
            $studentsGrade->setStudent($this);
        }

        return $this;
    }

    public function removeStudentsGrade(StudentsGrades $studentsGrade): self
    {
        if ($this->studentsGrades->removeElement($studentsGrade)) {
            // set the owning side to null (unless already changed)
            if ($studentsGrade->getStudent() === $this) {
                $studentsGrade->setStudent(null);
            }
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    /**
     * @param string|null $imagePath
     * @return $this
     */
    public function setImagePath(?string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param File|null $file
     * @return $this
     */
    public function setFile(?File $file): self
    {
        $this->file = $file;

        $this->setUpdatedAt(new DateTime());

        return $this;
    }
}
