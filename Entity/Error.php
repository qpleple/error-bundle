<?php
namespace Short\ErrorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="errors")
 * @ORM\Entity
 */
class Error
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @ORM\Column(name="code", type="integer", nullable=true) */
    protected $code;

    /** @ORM\Column(name="trace", type="text", nullable=true) */
    protected $trace;

    /** @ORM\Column(name="message", type="text", nullable=true) */
    protected $message;

    /** @ORM\ManyToOne(targetEntity="Short\SiteBundle\Entity\User") */
    protected $user;

    /** @ORM\Column(name="ip", type="string", length=255, nullable=true) */
    protected $ip;

    /** @ORM\Column(name="agent", type="string", length=255, nullable=true) */
    protected $agent;

    /** @ORM\Column(name="referer", type="string", length=255, nullable=true) */
    protected $referer;

    /** @ORM\Column(name="class", type="string", length=255, nullable=true) */
    protected $class;

    /** @ORM\Column(name="where_function", type="string", length=255, nullable=true) */
    protected $whereFunction;

    /** @ORM\Column(name="where_class", type="string", length=255, nullable=true) */
    protected $whereClass;

    /** @ORM\Column(name="url", type="string", length=255, nullable=true) */
    protected $url;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     * @Gedmo\Timestampable(on="create")
     */
    protected $createdAt;

    /**
     * @var string $fullName, ex : "Short\SiteBundle\Controller\MainController"
     * @return string, juste le nom de la class : "MainController"
     */
    public static function className($fullName)
    {
        $parts = array_reverse(explode('\\', $fullName));
        return $parts[0];
    }

    public function __toString()
    {
        return sprintf("%s in %s::%s()", self::className($this->class), self::className($this->whereClass), $this->whereFunction);
    }


    //// THE FOLLOWING HAS BEEN AUTO GENERATED ////


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
     * Set code
     *
     * @param integer $code
     * @return Error
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Get code
     *
     * @return integer 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set trace
     *
     * @param text $trace
     * @return Error
     */
    public function setTrace($trace)
    {
        $this->trace = $trace;
        return $this;
    }

    /**
     * Get trace
     *
     * @return text 
     */
    public function getTrace()
    {
        return $this->trace;
    }

    /**
     * Set message
     *
     * @param text $message
     * @return Error
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Get message
     *
     * @return text 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return Error
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set agent
     *
     * @param string $agent
     * @return Error
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * Get agent
     *
     * @return string 
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * Set referer
     *
     * @param string $referer
     * @return Error
     */
    public function setReferer($referer)
    {
        $this->referer = $referer;
        return $this;
    }

    /**
     * Get referer
     *
     * @return string 
     */
    public function getReferer()
    {
        return $this->referer;
    }

    /**
     * Set class
     *
     * @param string $class
     * @return Error
     */
    public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }

    /**
     * Get class
     *
     * @return string 
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set function
     *
     * @param string $function
     * @return Error
     */
    public function setFunction($function)
    {
        $this->function = $function;
        return $this;
    }

    /**
     * Get function
     *
     * @return string 
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Error
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     * @return Error
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return datetime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set user
     *
     * @param Short\SiteBundle\Entity\User $user
     * @return Error
     */
    public function setUser(\Short\SiteBundle\Entity\User $user = null)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return Short\SiteBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set whereFunction
     *
     * @param string $whereFunction
     * @return Error
     */
    public function setWhereFunction($whereFunction)
    {
        $this->whereFunction = $whereFunction;
        return $this;
    }

    /**
     * Get whereFunction
     *
     * @return string 
     */
    public function getWhereFunction()
    {
        return $this->whereFunction;
    }

    /**
     * Set whereClass
     *
     * @param string $whereClass
     * @return Error
     */
    public function setWhereClass($whereClass)
    {
        $this->whereClass = $whereClass;
        return $this;
    }

    /**
     * Get whereClass
     *
     * @return string 
     */
    public function getWhereClass()
    {
        return $this->whereClass;
    }
}