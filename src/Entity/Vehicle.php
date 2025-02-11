<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'vehicle')]
class Vehicle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 1)]
    private string $person;

    #[ORM\Column(type: 'bigint')]
    private int $regAddrKoatuu;

    #[ORM\Column(type: 'integer')]
    private int $operCode;

    #[ORM\Column(type: 'string', length: 255)]
    private string $operName;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $dReg;

    #[ORM\Column(type: 'integer')]
    private int $depCode;

    #[ORM\Column(type: 'string', length: 255)]
    private string $dep;

    #[ORM\Column(type: 'string', length: 50)]
    private string $brand;

    #[ORM\Column(type: 'string', length: 100)]
    private string $model;

    #[ORM\Column(type: 'string', length: 50)]
    private string $vin;

    #[ORM\Column(type: 'integer')]
    private int $makeYear;

    #[ORM\Column(type: 'string', length: 20)]
    private string $color;

    #[ORM\Column(type: 'string', length: 50)]
    private string $kind;

    #[ORM\Column(type: 'string', length: 50)]
    private string $body;

    #[ORM\Column(type: 'string', length: 50)]
    private string $purpose;

    #[ORM\Column(type: 'string', length: 50)]
    private string $fuel;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $capacity;

    #[ORM\Column(type: 'integer')]
    private int $ownWeight;

    #[ORM\Column(type: 'integer')]
    private int $totalWeight;

    #[ORM\Column(type: 'string', length: 20)]
    private string $nRegNew;

    public function getId(): int
    {
        return $this->id;
    }

    public function getPerson(): string
    {
        return $this->person;
    }

    public function setPerson(string $person): void
    {
        $this->person = $person;
    }

    public function getRegAddrKoatuu(): int
    {
        return $this->regAddrKoatuu;
    }

    public function setRegAddrKoatuu(int $regAddrKoatuu): void
    {
        $this->regAddrKoatuu = $regAddrKoatuu;
    }

    public function getOperCode(): int
    {
        return $this->operCode;
    }

    public function setOperCode(int $operCode): void
    {
        $this->operCode = $operCode;
    }

    public function getOperName(): string
    {
        return $this->operName;
    }

    public function setOperName(string $operName): void
    {
        $this->operName = $operName;
    }

    public function getDReg(): \DateTime
    {
        return $this->dReg;
    }

    public function setDReg(\DateTime $dReg): void
    {
        $this->dReg = $dReg;
    }

    public function getDepCode(): int
    {
        return $this->depCode;
    }

    public function setDepCode(int $depCode): void
    {
        $this->depCode = $depCode;
    }

    public function getDep(): string
    {
        return $this->dep;
    }

    public function setDep(string $dep): void
    {
        $this->dep = $dep;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function setModel(string $model): void
    {
        $this->model = $model;
    }

    public function getVin(): string
    {
        return $this->vin;
    }

    public function setVin(string $vin): void
    {
        $this->vin = $vin;
    }

    public function getMakeYear(): int
    {
        return $this->makeYear;
    }

    public function setMakeYear(int $makeYear): void
    {
        $this->makeYear = $makeYear;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): void
    {
        $this->color = $color;
    }

    public function getKind(): string
    {
        return $this->kind;
    }

    public function setKind(string $kind): void
    {
        $this->kind = $kind;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function getPurpose(): string
    {
        return $this->purpose;
    }

    public function setPurpose(string $purpose): void
    {
        $this->purpose = $purpose;
    }

    public function getFuel(): string
    {
        return $this->fuel;
    }

    public function setFuel(string $fuel): void
    {
        $this->fuel = $fuel;
    }

    public function getCapacity(): ?float
    {
        return $this->capacity;
    }

    public function setCapacity(?float $capacity): void
    {
        $this->capacity = $capacity;
    }

    public function getOwnWeight(): int
    {
        return $this->ownWeight;
    }

    public function setOwnWeight(int $ownWeight): void
    {
        $this->ownWeight = $ownWeight;
    }

    public function getTotalWeight(): int
    {
        return $this->totalWeight;
    }

    public function setTotalWeight(int $totalWeight): void
    {
        $this->totalWeight = $totalWeight;
    }

    public function getNRegNew(): string
    {
        return $this->nRegNew;
    }

    public function setNRegNew(string $nRegNew): void
    {
        $this->nRegNew = $nRegNew;
    }
}