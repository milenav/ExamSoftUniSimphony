<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Adjustment
 *
 * @ORM\Table(name="adjustments")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AdjustmentRepository")
 */
class Adjustment
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="adjustmentDate", type="date")
     */
    private $adjustmentDate;


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
     * Set adjustmentDate
     *
     * @param \DateTime $adjustmentDate
     *
     * @return Adjustment
     */
    public function setAdjustmentDate($adjustmentDate)
    {
        $this->adjustmentDate = $adjustmentDate;

        return $this;
    }

    /**
     * Get adjustmentDate
     *
     * @return \DateTime
     */
    public function getAdjustmentDate()
    {
        return $this->adjustmentDate;
    }
}

