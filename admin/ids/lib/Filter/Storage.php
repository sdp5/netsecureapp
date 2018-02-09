<?php

// This class provides various default functions for gathering filter patterns 
// to be used later on by the detection mechanism. 

class IDS_Filter_Storage
{

    protected $source = null;
    protected $cacheSettings = null;
    protected $cache = null;
    protected $filterSet = array();

    public final function __construct(IDS_Init $init) 
    {
        if ($init->config) {

            $caching = isset($init->config['Caching']['caching']) ? 
                $init->config['Caching']['caching'] : 'none';
                
            $type         = $init->config['General']['filter_type'];
            $this->source = $init->getBasePath() 
                . $init->config['General']['filter_path'];

            if ($caching && $caching != 'none') {
                $this->cacheSettings = $init->config['Caching'];
                include_once 'lib/Caching/Factory.php';
                $this->cache = IDS_Caching::factory($init, 'storage');
            }

            switch ($type) {
            case 'xml' :
                $this->getFilterFromXML();
                break;
            case 'json' :
                $this->getFilterFromJson();
                break;
            default :
                throw new Exception('Unsupported filter type.');
            }
        }
    }

    public final function setFilterSet($filterSet) 
    {
        foreach ($filterSet as $filter) {
            $this->addFilter($filter);
        }

        return $this;
    }

    public final function getFilterSet() 
    {
        return $this->filterSet;
    }

    public final function addFilter(IDS_Filter $filter) 
    {
        $this->filterSet[] = $filter;
        return $this;
    }

    private function _isCached() 
    {
        $filters = false;

        if ($this->cacheSettings) {
        
            if ($this->cache) {
                $filters = $this->cache->getCache();
            }
        }

        return $filters;
    }

    public function getFilterFromXML() 
    {

        if (extension_loaded('SimpleXML')) {

            /*
             * See if filters are already available in the cache
             */
            $filters = $this->_isCached();

            /*
             * If they aren't, parse the source file
             */
            if (!$filters) {
                if (file_exists($this->source)) {
                    if (LIBXML_VERSION >= 20621) {
                        $filters = simplexml_load_file($this->source,
                                                       null,
                                                       LIBXML_COMPACT);
                    } else {
                        $filters = simplexml_load_file($this->source);
                    }
                }
            }

            /*
             * In case we still don't have any filters loaded and exception
             * will be thrown
             */
            if (empty($filters)) {
                throw new Exception(
                    'XML data could not be loaded.' . 
                        ' Make sure you specified the correct path.'
                );
            }

            /*
             * Now the storage will be filled with IDS_Filter objects
             */
            $data    = array();
            $nocache = $filters instanceof SimpleXMLElement;
            $filters = $nocache ? $filters->filter : $filters;

            include_once 'lib/Filter.php';

            foreach ($filters as $filter) {

                $id          = $nocache ? (string) $filter->id : 
                    $filter['id'];
                $rule        = $nocache ? (string) $filter->rule : 
                    $filter['rule'];
                $impact      = $nocache ? (string) $filter->impact : 
                    $filter['impact'];
                $tags        = $nocache ? array_values((array) $filter->tags) : 
                    $filter['tags'];
                $description = $nocache ? (string) $filter->description : 
                    $filter['description'];

                $this->addFilter(new IDS_Filter($id,
                                                $rule,
                                                $description,
                                                (array) $tags[0],
                                                (int) $impact));

                $data[] = array(
                    'id'          => $id, 
                    'rule'        => $rule,
                    'impact'      => $impact,
                    'tags'        => $tags,
                    'description' => $description
                );
            }

            /*
             * If caching is enabled, the fetched data will be cached
             */
            if ($this->cacheSettings) {

                $this->cache->setCache($data);
            }

        } else {
            throw new Exception(
                'SimpleXML not loaded.'
            );
        }

        return $this;
    }
}