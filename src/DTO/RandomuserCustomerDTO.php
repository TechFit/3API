<?php


namespace App\DTO;


/**
 * Class RandomuserCustomerDTO
 */
class RandomuserCustomerDTO implements CustomerDTO
{
    /**
     * @var array
     */
    private array $name;

    /**
     * @var array
     */
    private array $location;

    /**
     * @var string
     */
    protected string $fullName;

    /**
     * @var string
     */
    private string $email;

    /**
     * @var string
     */
    private string $country;

    /**
     * @var string
     */
    private string $username;

    /**
     * @var string
     */
    private string $gender;

    /**
     * @var string
     */
    private string $city;

    /**
     * @var string
     */
    private string $phone;

    /**
     * @var array
     */
    private array $login;

    /**
     * @return array
     */
    public function getLocation(): array
    {
        return $this->location;
    }

    /**
     * @param array $location
     */
    public function setLocation(array $location): void
    {
        $this->location = $location;
    }

    /**
     * @return array
     */
    public function getName(): array
    {
        return $this->name;
    }

    /**
     * @param array $name
     */
    public function setName(array $name): void
    {
        $this->name = $name;
    }


    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->name['first'] . " " . $this->name['last'];
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->location['country'];
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->login['username'];
    }

    /**
     * @return string
     */
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->location['city'];
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param string $gender
     */
    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return array
     */
    public function getLogin(): array
    {
        return $this->login;
    }

    /**
     * @param array $login
     */
    public function setLogin(array $login): void
    {
        $this->login = $login;
    }
}