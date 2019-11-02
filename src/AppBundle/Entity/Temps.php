<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Temps
 *
 * @ORM\Table(name="temps")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TempsRepository")
 */
class Temps
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
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="exercice", type="integer")
     */
    private $exercice;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Dossier", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true,onDelete="SET NULL")
     */
    private $dossier;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true,onDelete="SET NULL")
     */
    private $collaborateur;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tache", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true,onDelete="SET NULL")
     */
    private $tache;

    /**
     * @var decimal
     *
     * @ORM\Column(name="tempspasse", type="decimal", precision=9, scale=2)
     */
    private $tempspasse;

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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Temps
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set exercice
     *
     * @param integer $exercice
     *
     * @return Temps
     */
    public function setExercice($exercice)
    {
        $this->exercice = $exercice;

        return $this;
    }

    /**
     * Get exercice
     *
     * @return int
     */
    public function getExercice()
    {
        return $this->exercice;
    }

    /**
     * Set dossier
     *
     * @param integer $dossier
     *
     * @return Temps
     */
    public function setDossier($dossier)
    {
        $this->dossier = $dossier;

        return $this;
    }

    /**
     * Get dossier
     *
     * @return int
     */
    public function getDossier()
    {
        return $this->dossier;
    }

    /**
     * Set tache
     *
     * @param integer $tache
     *
     * @return Temps
     */
    public function setTache($tache)
    {
        $this->tache = $tache;

        return $this;
    }

    /**
     * Get tache
     *
     * @return int
     */
    public function getTache()
    {
        return $this->tache;
    }

    /**
     * Set tempspasse
     *
     * @param decimal $tempspasse
     *
     * @return Temps
     */
    public function setTempspasse($tempspasse)
    {
        $this->tempspasse = $tempspasse;

        return $this;
    }

    /**
     * Get tempspasse
     *
     * @return decimal
     */
    public function getTempspasse()
    {
        return $this->tempspasse;
    }

    /**
     * Set collaborateur
     *
     * @param \AppBundle\Entity\User $collaborateur
     *
     * @return Temps
     */
    public function setCollaborateur(\AppBundle\Entity\User $collaborateur = null)
    {
        $this->collaborateur = $collaborateur;

        return $this;
    }

    /**
     * Get collaborateur
     *
     * @return \AppBundle\Entity\User
     */
    public function getCollaborateur()
    {
        return $this->collaborateur;
    }
}
