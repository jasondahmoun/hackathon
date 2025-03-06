<?php

namespace App\Tests\Entity;

use App\Entity\Employee;
use App\Entity\Chantier;
use App\Entity\Affectation;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Collections\ArrayCollection;

class EmployeeTest extends TestCase
{
    public function testEmployeeProperties()
    {
        $employee = new Employee();
        $employee->setNom('Doe');
        $employee->setPrenom('John');
        $employee->setEmail('john.doe@example.com');
        $employee->setRole('ROLE_WORKER');
        $employee->setDisponibilite(true);

        $this->assertEquals('Doe', $employee->getNom());
        $this->assertEquals('John', $employee->getPrenom());
        $this->assertEquals('john.doe@example.com', $employee->getEmail());
        $this->assertEquals('ROLE_WORKER', $employee->getRole());
        $this->assertTrue($employee->isDisponibilite());
    }

    public function testChantiers()
    {
        $employee = new Employee();
        $chantier = $this->createMock(Chantier::class);

        $employee->addChantier($chantier);
        $this->assertCount(1, $employee->getChantiers());
        $this->assertTrue($employee->getChantiers()->contains($chantier));

        $employee->removeChantier($chantier);
        $this->assertCount(0, $employee->getChantiers());
    }

    public function testChantierActuel()
    {
        $employee = new Employee();
        $chantier = $this->createMock(Chantier::class);

        $employee->setChantierActuel($chantier);
        $this->assertSame($chantier, $employee->getChantierActuel());
    }

    public function testAffectations()
    {
        $employee = new Employee();
        $affectation = $this->createMock(Affectation::class);

        $employee->addAffectation($affectation);
        $this->assertCount(1, $employee->getAffectations());
        $this->assertTrue($employee->getAffectations()->contains($affectation));

        $employee->removeAffectation($affectation);
        $this->assertCount(0, $employee->getAffectations());
    }

    public function testToString()
    {
        $employee = new Employee();
        $employee->setNom('Doe');
        $employee->setPrenom('John');

        $this->assertEquals('Doe John', (string) $employee);
    }
}
