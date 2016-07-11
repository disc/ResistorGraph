<?php


class ResistorGraph
{
    const NOT_REACHABLE = 3000000000;

    protected $resistanceValue = array();

    protected $neighbors = array();

    protected $graph = array();

    protected $nodesCount;

    protected $shortestPath = array();

    public function __construct($graph)
    {
        $this->graph = $graph;
        $this->nodesCount = count($this->graph);
        $this->init();
    }

    private function init()
    {
        for ($i = 0; $i < $this->nodesCount; $i++) {
            for ($j = 0; $j < $this->nodesCount; $j++) {
                // if equals
                if ($i === $j) {
                    $this->resistanceValue[$i][$j] = 0;
                } else if ($this->graph[$i][$j] > 0) {
                    // set weight
                    $this->resistanceValue[$i][$j] = $this->graph[$i][$j];
                } else {
                    // if not available
                    $this->resistanceValue[$i][$j] = self::NOT_REACHABLE;
                }
                $this->neighbors[$i][$j] = $i;
            }
        }
        for ($k = 0; $k < $this->nodesCount; $k++) {
            for ($i = 0; $i < $this->nodesCount; $i++) {
                for ($j = 0; $j < $this->nodesCount; $j++) {
                    // looking for minimum of resistance
                    if ($this->resistanceValue[$i][$j] > ($this->resistanceValue[$i][$k] + $this->resistanceValue[$k][$j])) {
                        // calculate sum of resistance
                        $this->resistanceValue[$i][$j] = $this->resistanceValue[$i][$k] + $this->resistanceValue[$k][$j];
                        $this->neighbors[$i][$j] = $this->neighbors[$k][$j];
                    }
                }
            }
        }
    }

    protected function _calculatePath($from, $to, &$result)
    {
        if ($from !== $to) {
            $this->_calculatePath($from, $this->neighbors[$from][$to], $result);
        }
        $result[] = $to;
    }

    protected function findShortestPath($from, $to)
    {
        $result = array();
        if ($this->isNodeExists($from) && $this->isNodeExists($to)) {
            $this->_calculatePath($from, $to, $result);
        }
        $this->shortestPath = $result;
    }

    protected function isNodeExists($node) {
        return array_key_exists($node, $this->neighbors);
    }

    /**
     * @return array
     */
    public function getShortestPath()
    {
        return $this->shortestPath;
    }

    public function getResistanceBetweenPoints($from, $to)
    {
        $this->findShortestPath($from, $to);
        if (isset($this->resistanceValue[$from][$to])) {
            return $this->resistanceValue[$from][$to];
        }
        return 0;
    }
}