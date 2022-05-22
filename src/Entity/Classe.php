<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\ClasseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;

#[
ORM\Entity(repositoryClass: ClasseRepository::class)]
#[ApiResource(
    collectionOperations: [
        'POST' => [
            "security" => "is_granted('ROLE_ADMIN')"
        ],
        'GET'
    ],
    itemOperations: [
        'PUT' => [
            "security" => "is_granted('ROLE_ADMIN')"
        ],
        'DELETE' => [
            "security" => "is_granted('ROLE_ADMIN')"
        ],
        'GET'
    ],
    denormalizationContext: [
        'groups' => ['write:Classe']
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
    ApiFilter(SearchFilter::class, properties: ['name' => 'partial', 'section' => 'partial'])
]

class Classe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups('read:collection')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[
        Groups(['read:collection', 'write:Classe', 'read:Course']),
        Length(min: 3)
    ]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[
        Groups(['read:collection', 'write:Classe', 'read:Course']),
        Length(min: 3)
    ]
    private $section;

    #[ORM\OneToMany(mappedBy: 'classe', targetEntity: Course::class)]
    private $courses;

    #[ORM\OneToMany(mappedBy: 'classe', targetEntity: Student::class)]
    private $students;

    #[ORM\OneToMany(mappedBy: 'classe', targetEntity: StudentsGrades::class)]
    private $studentsGrades;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
        $this->students = new ArrayCollection();
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

    public function getSection(): ?string
    {
        return $this->section;
    }

    public function setSection(string $section): self
    {
        $this->section = $section;

        return $this;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses[] = $course;
            $course->setClasse($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): self
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getClasse() === $this) {
                $course->setClasse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): self
    {
        if (!$this->students->contains($student)) {
            $this->students[] = $student;
            $student->setClasse($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getClasse() === $this) {
                $student->setClasse(null);
            }
        }

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
            $studentsGrade->setClasse($this);
        }

        return $this;
    }

    public function removeStudentsGrade(StudentsGrades $studentsGrade): self
    {
        if ($this->studentsGrades->removeElement($studentsGrade)) {
            // set the owning side to null (unless already changed)
            if ($studentsGrade->getClasse() === $this) {
                $studentsGrade->setClasse(null);
            }
        }

        return $this;
    }
}
