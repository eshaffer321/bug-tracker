<?php
namespace App\Models;
/**
 * @Entity @Table(name="users")
 */
class User
{
 

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string")
     */
    private $password;

    /**
     * @var integer
     *
     * @ORM\Column(name="authenticated", type="integer")
     */
    private $authenticated;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", nullable=false)
     */
    private $role;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Bug", mappedBy="reporter")
     */
    private $reportedBugs;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Bug", mappedBy="engineer")
     */
    private $assignedBugs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reportedBugs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->assignedBugs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
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
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
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
     * Set authenticated
     *
     * @param integer $authenticated
     * @return User
     */
    public function setAuthenticated($authenticated)
    {
        $this->authenticated = $authenticated;

        return $this;
    }

    /**
     * Get authenticated
     *
     * @return integer 
     */
    public function getAuthenticated()
    {
        return $this->authenticated;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Add reportedBugs
     *
     * @param \Bug $reportedBugs
     * @return User
     */
    public function addReportedBug(\Bug $reportedBugs)
    {
        $this->reportedBugs[] = $reportedBugs;

        return $this;
    }

    /**
     * Remove reportedBugs
     *
     * @param \Bug $reportedBugs
     */
    public function removeReportedBug(\Bug $reportedBugs)
    {
        $this->reportedBugs->removeElement($reportedBugs);
    }

    /**
     * Get reportedBugs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReportedBugs()
    {
        return $this->reportedBugs;
    }

    /**
     * Add assignedBugs
     *
     * @param \Bug $assignedBugs
     * @return User
     */
    public function addAssignedBug(\Bug $assignedBugs)
    {
        $this->assignedBugs[] = $assignedBugs;

        return $this;
    }

    /**
     * Remove assignedBugs
     *
     * @param \Bug $assignedBugs
     */
    public function removeAssignedBug(\Bug $assignedBugs)
    {
        $this->assignedBugs->removeElement($assignedBugs);
    }

    /**
     * Get assignedBugs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAssignedBugs()
    {
        return $this->assignedBugs;
    }
}
