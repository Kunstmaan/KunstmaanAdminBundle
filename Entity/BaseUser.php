<?php

namespace Kunstmaan\AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\GroupInterface;
use FOS\UserBundle\Model\User as AbstractUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

abstract class BaseUser extends AbstractUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * The doctrine metadata is set dynamically in Kunstmaan\AdminBundle\EventListener\MappingListener
     */
    protected $groups;

    /**
     * Construct a new user
     */
    public function __construct()
    {
        parent::__construct();
        $this->groups = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id
     *
     * @param int $id
     *
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the groupIds for the user.
     *
     * @return array
     */
    public function getGroupIds()
    {
        $groups = $this->groups;

        $groupIds = array();
        if (count($groups) > 0) {
            /* @var $group GroupInterface */
            foreach ($groups as $group) {
                $groupIds[] = $group->getId();
            }
        }

        return $groupIds;
    }

    /**
     * Gets the groups the user belongs to.
     *
     * @return ArrayCollection
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('username', new NotBlank());
        $metadata->addPropertyConstraint('plainPassword', new NotBlank(array("groups" => array("Registration"))));
        $metadata->addPropertyConstraint('email', new NotBlank());
        $metadata->addPropertyConstraint('email', new Email());
        $metadata->addConstraint(new UniqueEntity(array(
            'fields'  => 'username',
            'message' => 'errors.user.loginexists',
        )));
        $metadata->addConstraint(new UniqueEntity(array(
            'fields'  => 'email',
            'message' => 'errors.user.emailexists',
        )));
    }

    abstract protected function getFormTypeClass();
    abstract protected function getAdminListConfiguratorClass();
}
