<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
#[ApiResource(
    collectionOperations: [
        'post' => [
            "security" => "is_granted('ROLE_ADMIN')"
        ],
        'get'
    ],
    itemOperations: [
        'put' => [
            "security" => "is_granted('ROLE_ADMIN')"
        ],
        'delete' => [
            "security" => "is_granted('ROLE_ADMIN')"
        ],
        'get' => [
            'normalization_context' => [
                'groups' => ['read:collection', 'read:item', 'read:Course'],
                'openapi_definition_name' => 'Detail'
            ]
        ]
    ],
    denormalizationContext: [
        'groups' => ['write:Course']
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
    ApiFilter(SearchFilter::class, properties: ['name' => 'partial', 'description' => 'partial', 'classe.name' => 'partial'])
]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups('read:collection')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[
        Groups(['read:collection','write:Course']),
        Length(min: 3)
    ]
    private $name;

    #[ORM\Column(type: 'text')]
    #[
        Groups(['read:item','write:Course']),
        Length(min: 3)
    ]
    private $description;

    #[ORM\ManyToOne(targetEntity: Classe::class,cascade: ['remove'], inversedBy: 'courses')]
    #[Groups(['read:collection','write:Course'])]
    private $classe;

    #[ORM\OneToMany(mappedBy: 'course', targetEntity: StudentsGrades::class)]
    private $studentsGrades;

    public function __construct()
    {
        $this->studentsGrades = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
            $studentsGrade->setCourse($this);
        }

        return $this;
    }

    public function removeStudentsGrade(StudentsGrades $studentsGrade): self
    {
        if ($this->studentsGrades->removeElement($studentsGrade)) {
            // set the owning side to null (unless already changed)
            if ($studentsGrade->getCourse() === $this) {
                $studentsGrade->setCourse(null);
            }
        }

        return $this;
    }
}
