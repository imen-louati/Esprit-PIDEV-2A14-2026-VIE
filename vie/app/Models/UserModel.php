<?php

namespace App\Models;

class UserModel
{
    private int $id = 0;
    private string $firstName = '';
    private string $lastName = '';
    private string $email = '';
    private string $password = '';
    private string $phone = '';
    private string $role = 'patient';
    private string $status = 'active';
    private string $birthDate = '';
    private string $shareCode = '';
    private string $cancerType = '';
    private string $stage = '';
    private string $progress = '';
    private string $speciality = '';
    private string $licenseNumber = '';
    private string $city = '';
    private int $patientId = 0;
    private string $relationship = '';
    private bool $shareAppointments = false;
    private bool $shareMedications = false;
    private bool $shareDocuments = false;
    private string $accessExpiresAt = '';
    private string $accessRevokedAt = '';
    private string $lastLoginAt = '';
    private string $createdAt = '';

    public function __construct()
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getBirthDate(): string
    {
        return $this->birthDate;
    }

    public function getShareCode(): string
    {
        return $this->shareCode;
    }

    public function getCancerType(): string
    {
        return $this->cancerType;
    }

    public function getStage(): string
    {
        return $this->stage;
    }

    public function getProgress(): string
    {
        return $this->progress;
    }

    public function getSpeciality(): string
    {
        return $this->speciality;
    }

    public function getLicenseNumber(): string
    {
        return $this->licenseNumber;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getPatientId(): int
    {
        return $this->patientId;
    }

    public function getRelationship(): string
    {
        return $this->relationship;
    }

    public function isShareAppointments(): bool
    {
        return $this->shareAppointments;
    }

    public function isShareMedications(): bool
    {
        return $this->shareMedications;
    }

    public function isShareDocuments(): bool
    {
        return $this->shareDocuments;
    }

    public function getAccessExpiresAt(): string
    {
        return $this->accessExpiresAt;
    }

    public function getAccessRevokedAt(): string
    {
        return $this->accessRevokedAt;
    }

    public function getLastLoginAt(): string
    {
        return $this->lastLoginAt;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getFullName(): string
    {
        return trim($this->firstName . ' ' . $this->lastName);
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function setBirthDate(string $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    public function setShareCode(string $shareCode): void
    {
        $this->shareCode = $shareCode;
    }

    public function setCancerType(string $cancerType): void
    {
        $this->cancerType = $cancerType;
    }

    public function setStage(string $stage): void
    {
        $this->stage = $stage;
    }

    public function setProgress(string $progress): void
    {
        $this->progress = $progress;
    }

    public function setSpeciality(string $speciality): void
    {
        $this->speciality = $speciality;
    }

    public function setLicenseNumber(string $licenseNumber): void
    {
        $this->licenseNumber = $licenseNumber;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function setPatientId(int $patientId): void
    {
        $this->patientId = $patientId;
    }

    public function setRelationship(string $relationship): void
    {
        $this->relationship = $relationship;
    }

    public function setShareAppointments(bool $shareAppointments): void
    {
        $this->shareAppointments = $shareAppointments;
    }

    public function setShareMedications(bool $shareMedications): void
    {
        $this->shareMedications = $shareMedications;
    }

    public function setShareDocuments(bool $shareDocuments): void
    {
        $this->shareDocuments = $shareDocuments;
    }

    public function setAccessExpiresAt(string $accessExpiresAt): void
    {
        $this->accessExpiresAt = $accessExpiresAt;
    }

    public function setAccessRevokedAt(string $accessRevokedAt): void
    {
        $this->accessRevokedAt = $accessRevokedAt;
    }

    public function setLastLoginAt(string $lastLoginAt): void
    {
        $this->lastLoginAt = $lastLoginAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public static function fromArray(array $row): self
    {
        $user = new self();
        $user->setId((int) ($row['id'] ?? 0));
        $user->setFirstName((string) ($row['first_name'] ?? ''));
        $user->setLastName((string) ($row['last_name'] ?? ''));
        $user->setEmail((string) ($row['email'] ?? ''));
        $user->setPassword((string) ($row['password'] ?? ''));
        $user->setPhone((string) ($row['phone'] ?? ''));
        $user->setRole((string) ($row['role'] ?? 'patient'));
        $user->setStatus((string) ($row['status'] ?? 'active'));
        $user->setBirthDate((string) ($row['birth_date'] ?? ''));
        $user->setShareCode((string) ($row['share_code'] ?? ''));
        $user->setCancerType((string) ($row['cancer_type'] ?? ''));
        $user->setStage((string) ($row['stage'] ?? ''));
        $user->setProgress((string) ($row['progress'] ?? ''));
        $user->setSpeciality((string) ($row['speciality'] ?? ''));
        $user->setLicenseNumber((string) ($row['license_number'] ?? ''));
        $user->setCity((string) ($row['city'] ?? ''));
        $user->setPatientId((int) ($row['patient_id'] ?? 0));
        $user->setRelationship((string) ($row['relationship'] ?? ''));
        $user->setShareAppointments((bool) ($row['share_appointments'] ?? false));
        $user->setShareMedications((bool) ($row['share_medications'] ?? false));
        $user->setShareDocuments((bool) ($row['share_documents'] ?? false));
        $user->setAccessExpiresAt((string) ($row['access_expires_at'] ?? ''));
        $user->setAccessRevokedAt((string) ($row['access_revoked_at'] ?? ''));
        $user->setLastLoginAt((string) ($row['last_login_at'] ?? ''));
        $user->setCreatedAt((string) ($row['created_at'] ?? ''));
        return $user;
    }
}
