<?php

// This class represents a certain event that occured while applying the filters 
// to the supplied data. It aggregates a bunch of IDS_Filter implementations and 
// is a assembled in IDS_Report.

class IDS_Event implements Countable, IteratorAggregate
{

    protected $name = null;
    protected $value = null;
    protected $filters = array();
    protected $tags = array();

	public function __construct($name, $value, Array $filters) 
    {
        if (!is_scalar($name)) {
            throw new InvalidArgumentException(
                'Expected $name to be a scalar,' . gettype($name) . ' given'
            );
        }

        if (!is_scalar($value)) {
            throw new InvalidArgumentException('
                Expected $value to be a scalar,' . gettype($value) . ' given'
            );
        }

        $this->name  = $name;
        $this->value = $value;

        foreach ($filters as $filter) {
            if (!$filter instanceof IDS_Filter) {
                throw new InvalidArgumentException(
                    'Filter must be derived from IDS_Filter'
                );
            }

            $this->filters[] = $filter;
        }
    }

    public function getName() 
    {
        return $this->name;
    }

    public function getValue() 
    {
        return $this->value;
    }

    public function getImpact() 
    {
        if (!$this->impact) {
            $this->impact = 0;
            foreach ($this->filters as $filter) {
                $this->impact += $filter->getImpact();
            }
        }

        return $this->impact;
    }

    public function getTags() 
    {
        $filters = $this->getFilters();

        foreach ($filters as $filter) {
            $this->tags = array_merge($this->tags,
                                      $filter->getTags());
        }

        $this->tags = array_values(array_unique($this->tags));

        return $this->tags;
    }

    public function getFilters() 
    {
        return $this->filters;
    }

    public function count() 
    {
        return count($this->getFilters());
    }

    public function getIterator() 
    {
        return new ArrayObject($this->getFilters());
    }
}