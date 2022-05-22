<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\StudentsGradesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: StudentsGradesRepository::class)]
#[ApiResource(
    collectionOperations: [
        'post' => [
            "security" => "is_granted('ROLE_ADMIN')"
        ],
        'get'
    ],
    itemOperations: [
        'get',
        'delete' => [
            "security" => "is_granted('ROLE_ADMIN')"
        ],
        'put' => [
            "security" => "is_granted('ROLE_ADMIN')"
        ]
    ],
    denormalizationContext: [
        'groups' => ['write:StudentsGrades']
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
    ApiFilter(SearchFilter::class, properties: ['student.firstname' => 'partial', 'student.lastname' => 'partial', 'course.name' => 'partial', 'classe.name' => 'partial', 'grade' => 'exact'])
]
class StudentsGrades
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['read:collection'])]
    private $id;

    #[ORM\ManyToOne(targetEntity: Student::class,cascade: ['remove'], inversedBy: 'studentsGrades')]
    #[Groups(['read:collection','write:StudentsGrades'])]
    private $student;

    #[ORM\ManyToOne(targetEntity: Course::class,cascade: ['remove'], inversedBy: 'studentsGrades')]
    #[Groups(['read:collection','write:StudentsGrades'])]
    private $course;

    #[ORM\ManyToOne(targetEntity: Classe::class,cascade: ['remove'], inversedBy: 'studentsGrades')]
    #[Groups(['read:collection','write:StudentsGrades'])]
    private $classe;

    #[ORM\Column(type: 'decimal', precision: 10, scale: '0')]
    #[Groups(['read:collection','write:StudentsGrades'])]
    private $grade;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

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

    public function getGrade(): ?string
    {
        return $this->grade;
    }

    public function setGrade(string $grade): self
    {
        $this->grade = $grade;

        return $this;
    }
}
