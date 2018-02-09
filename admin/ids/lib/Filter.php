<?php


// Each object of this class serves as a container for a specific filter. The 
// object provides methods to get information about this particular filter and 
// else match an arbitary string against it.
 
class IDS_Filter
{

    protected $rule;
    protected $tags = array();
    protected $impact = 0;
    protected $description = null;

    public function __construct($id, $rule, $description, array $tags, $impact) 
    {
    	$this->id          = $id;
        $this->rule        = $rule;
        $this->tags        = $tags;
        $this->impact      = $impact;
        $this->description = $description;
    }

    public function match($string)
    {
        if (!is_string($string)) {
            throw new InvalidArgumentException('
                Invalid argument. Expected a string, received ' . gettype($string)
            );
        }

        return (bool) preg_match(
            '/' . $this->getRule() . '/ms', strtolower($string)
        );
    }

	public function getDescription() 
    {
        return $this->description;
    }

    public function getTags() 
    {
        return $this->tags;
    }

    public function getRule() 
    {
        return $this->rule;
    }

    public function getImpact() 
    {
        return $this->impact;
    }
    
    
    public function getId() 
    {
    	return $this->id;
    }
}
