<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Form\Data;

use Symfony\Component\Validator\Constraints as Assert;

class ContactData
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    private $firstName;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    private $lastName;

    /**
     * @var string
     *
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    private $message;

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getFrom(): string
    {
        return sprintf('%s %s <%s>',
            $this->getFirstName(),
            $this->getLastName(),
            $this->getEmail()
        );
    }
}
