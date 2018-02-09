<?php


// The report objects collects a number of events and thereby presents the
// detected results. It provides a convenient API to work with the results.

class IDS_Report implements Countable, IteratorAggregate
{

    protected $events = array();
    protected $tags = array();
    protected $impact = 0;
    protected $centrifuge = array();
    public function __construct(array $events = null)
    {
        if ($events) {
            foreach ($events as $event) {
                $this->addEvent($event);
            }
        }
    }

	public function addEvent(IDS_Event $event)
    {
        $this->clear();
        $this->events[$event->getName()] = $event;

        return $this;
    }

    public function getEvent($name)
    {
        if (!is_scalar($name)) {
            throw new InvalidArgumentException(
                'Invalid argument type given'
            );
        }

        if ($this->hasEvent($name)) {
            return $this->events[$name];
        }

        return false;
    }

    public function getTags()
    {
        if (!$this->tags) {
            $this->tags = array();

            foreach ($this->events as $event) {
                $this->tags = array_merge($this->tags,
                                          $event->getTags());
            }

            $this->tags = array_values(array_unique($this->tags));
        }

        return $this->tags;
    }

    public function getImpact()
    {
        if (!$this->impact) {
            $this->impact = 0;
            foreach ($this->events as $event) {
                $this->impact += $event->getImpact();
            }
        }

        return $this->impact;
    }

    public function hasEvent($name)
    {
        if (!is_scalar($name)) {
            throw new InvalidArgumentException('Invalid argument given');
        }

        return isset($this->events[$name]);
    }

    public function count()
    {
        return count($this->events);
    }

    public function getIterator()
    {
        return new ArrayObject($this->events);
    }

    public function isEmpty()
    {
        return empty($this->events);
    }

    protected function clear()
    {
        $this->impact = 0;
        $this->tags   = array();
    }

    public function getCentrifuge()
    {
        return ($this->centrifuge && count($this->centrifuge) > 0)
            ? $this->centrifuge : null;
    }

    public function setCentrifuge($centrifuge = array())
    {
        if (is_array($centrifuge) && $centrifuge) {
            $this->centrifuge = $centrifuge;
            return true;
        }
        throw new InvalidArgumentException('Invalid argument given');
    }

    public function __toString()
    {
        if (!$this->isEmpty()) {
            $output  = '';
            $output .= 'Total impact: ' . $this->getImpact() . "<br/>\n";
            $output .= 'Affected tags: ' . join(', ', $this->getTags()) .
                "<br/>\n";

            foreach ($this->events as $event) {
                $output .= "<br/>\nVariable: " .
                    htmlspecialchars($event->getName()) . ' | Value: ' .
                    htmlspecialchars($event->getValue()) . "<br/>\n";
                $output .= 'Impact: ' . $event->getImpact() . ' | Tags: ' .
                    join(', ', $event->getTags()) . "<br/>\n";

                foreach ($event as $filter) {
                    $output .= 'Description: ' . $filter->getDescription() .
                        ' | ';
                    $output .= 'Tags: ' . join(', ', $filter->getTags()) .
                        ' | ';
                    $output .= 'ID: ' . $filter->getId() .
                        "<br/>\n";
                }
            }

            $output .= '<br/>';

            if ($centrifuge = $this->getCentrifuge()) {
                $output .= 'Centrifuge detection data';
                $output .= '<br/>  Threshold: ' . 
                    ((isset($centrifuge['threshold'])&&$centrifuge['threshold']) ?
                    $centrifuge['threshold'] : '---');
                $output .= '<br/>  Ratio: ' . 
                    ((isset($centrifuge['ratio'])&&$centrifuge['ratio']) ?
                    $centrifuge['ratio'] : '---');
                if(isset($centrifuge['converted'])) {
                    $output .= '<br/>  Converted: ' . $centrifuge['converted'];
                }
                $output .= "<br/><br/>\n";
            }
        }

        return isset($output) ? $output : '';
    }
}