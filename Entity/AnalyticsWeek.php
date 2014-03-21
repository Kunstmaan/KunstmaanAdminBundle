<?php

namespace Kunstmaan\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnalyticsWeek
 *
 * @ORM\Table(name="kumaga_analytics_week")
 * @ORM\Entity(repositoryClass="Kunstmaan\AdminBundle\Repository\AnalyticsWeekRepository")
 */
class AnalyticsWeek extends \Kunstmaan\AdminBundle\Entity\AbstractEntity
{
    /**
     * @var timestamp
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="day1", type="integer")
     */
    private $day1 = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="day2", type="integer")
     */
    private $day2 = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="day3", type="integer")
     */
    private $day3 = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="day4", type="integer")
     */
    private $day4 = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="day5", type="integer")
     */
    private $day5 = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="day6", type="integer")
     */
    private $day6 = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="day7", type="integer")
     */
    private $day7 = 0;


    /**
     * Set date
     *
     * @param date $date
     * @return AnalyticsWeek
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return date
     */
    public function getDate()
    {
        return $this->date;
    }


    /**
     * Set day1
     *
     * @param integer $day1
     * @return AnalyticsWeek
     */
    public function setDay1($day1)
    {
        $this->day1 = $day1;

        return $this;
    }

    /**
     * Get day1
     *
     * @return integer
     */
    public function getDay1()
    {
        return $this->day1;
    }

    /**
     * Set day2
     *
     * @param integer $day2
     * @return AnalyticsWeek
     */
    public function setDay2($day2)
    {
        $this->day2 = $day2;

        return $this;
    }

    /**
     * Get day2
     *
     * @return integer
     */
    public function getDay2()
    {
        return $this->day2;
    }

    /**
     * Set day3
     *
     * @param integer $day3
     * @return AnalyticsWeek
     */
    public function setDay3($day3)
    {
        $this->day3 = $day3;

        return $this;
    }

    /**
     * Get day3
     *
     * @return integer
     */
    public function getDay3()
    {
        return $this->day3;
    }

    /**
     * Set day4
     *
     * @param integer $day4
     * @return AnalyticsWeek
     */
    public function setDay4($day4)
    {
        $this->day4 = $day4;

        return $this;
    }

    /**
     * Get day4
     *
     * @return integer
     */
    public function getDay4()
    {
        return $this->day4;
    }

    /**
     * Set day5
     *
     * @param integer $day5
     * @return AnalyticsWeek
     */
    public function setDay5($day5)
    {
        $this->day5 = $day5;

        return $this;
    }

    /**
     * Get day5
     *
     * @return integer
     */
    public function getDay5()
    {
        return $this->day5;
    }

    /**
     * Set day6
     *
     * @param integer $day6
     * @return AnalyticsWeek
     */
    public function setDay6($day6)
    {
        $this->day6 = $day6;

        return $this;
    }

    /**
     * Get day6
     *
     * @return integer
     */
    public function getDay6()
    {
        return $this->day6;
    }

    /**
     * Set day7
     *
     * @param integer $day7
     * @return AnalyticsWeek
     */
    public function setDay7($day7)
    {
        $this->day7 = $day7;

        return $this;
    }

    /**
     * Get day7
     *
     * @return integer
     */
    public function getDay7()
    {
        return $this->day7;
    }
}
