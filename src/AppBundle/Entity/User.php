<?php
namespace AppBundle\Entity;

use AppBundle\Entity\Enumerations\UserRole;
use AppBundle\Form\Type\UserType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table("user")
 * @ORM\Entity
 * @ORM\EntityListeners({"AppBundle\EventListener\UserListener"})
 * @UniqueEntity(
 *     "email",
 *     message="Cette adresse mail n'est pas disponible."
 * )
 * @UniqueEntity(
 *     "username",
 *     message="Ce nom d'utilisateur n'est pas disponible."
 * )
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank(message="Vous devez saisir un nom d'utilisateur.")
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * Store plain password before encoding
     *
     * @var string
     *
     * @Assert\NotBlank()
     */
    private $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank(message="Vous devez saisir une adresse email.")
     * @Assert\Email(message="Le format de l'adresse n'est pas correcte.")
     */
    private $email;

    /**
     * Role of user (ADMIN/USER)
     *
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=5)
     */
    private $role;

    /**
     * Get id
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username
     *
     * @param $username string
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Salt for password encoding
     *
     * @return null
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password
     *
     * @param $password string
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email
     *
     * @param $email User email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Return
     *
     * @return array User roles
     */
    public function getRoles() : array
    {
        return [UserRole::getValue($this->getRole())];
    }

    /**
     * Erase credentials after password encode
     */
    public function eraseCredentials()
    {
        $this->setPlainPassword(null);
    }

    /**
     * Set role
     *
     * @param string $role
     *
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function setRole(string $role) : User
    {
        // if unknow role, throw execption
        if (!in_array($role, UserRole::getAvailableRoles())) {
            throw new \InvalidArgumentException("Invalid UserRole");
        }

        $this->role = $role;

        return $this;
    }

    /**
     * Get role.
     *
     * @return string
     */
    public function getRole()
    {
        if($this->role !== null) return $this->role;
    }

    /**
     * Get plainPassword
     *
     * @return string|null
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set plainPassword
     *
     * @param string $plainPassword
     *
     * @return $this
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }
}
