<?php
/**
 * @Entity(repositoryClass="BugRepository") @Table(name="bugs")
 */
namespace App\Models;
/**
 * @ORM\Entity
 * @App\Models\config\yaml
 */
class Bug
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
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string")
     */
    private $status;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="reportedBugs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="reporter_id", referencedColumnName="id")
     * })
     */
    private $reporter;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="assignedBugs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="engineer_id", referencedColumnName="id")
     * })
     */
    private $engineer;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Product")
     * @ORM\JoinTable(name="bug_product",
     *   joinColumns={
     *     @ORM\JoinColumn(name="bug_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     */
    private $products;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set description
     *
     * @param string $description
     * @return Bug
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Bug
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Bug
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set reporter
     *
     * @param \User $reporter
     * @return Bug
     */
    public function setReporter(\User $reporter = null)
    {
        $this->reporter = $reporter;

        return $this;
    }

    /**
     * Get reporter
     *
     * @return \User 
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    /**
     * Set engineer
     *
     * @param \User $engineer
     * @return Bug
     */
    public function setEngineer(\User $engineer = null)
    {
        $this->engineer = $engineer;

        return $this;
    }

    /**
     * Get engineer
     *
     * @return \User 
     */
    public function getEngineer()
    {
        return $this->engineer;
    }

    /**
     * Add products
     *
     * @param \Product $products
     * @return Bug
     */
    public function addProduct(\Product $products)
    {
        $this->products[] = $products;

        return $this;
    }

    /**
     * Remove products
     *
     * @param \Product $products
     */
    public function removeProduct(\Product $products)
    {
        $this->products->removeElement($products);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProducts()
    {
        return $this->products;
    }
}
