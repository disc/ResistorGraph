<?php

require_once 'ResistorGraph.php';
require_once 'GraphData.php';

$nodeLabel = GraphData::getNodeLabelList();
$resistorGraph = new ResistorGraph(GraphData::getMatrix());

$fromLabel = !empty($argv[1]) ? $argv[1] : null;
$toLabel = !empty($argv[2]) ? $argv[2] : null;

if (($from = array_search($fromLabel, $nodeLabel, null)) !== false && ($to = array_search($toLabel, $nodeLabel, null)) !== false) {
    $resistanceValue = $resistorGraph->getResistanceBetweenPoints($from, $to);
} else {
    exit("Incorrect value pointA or pointB\n");
}

echo "Resistance value is {$resistanceValue}\n";
$shortPath = array();
foreach ($resistorGraph->getShortestPath() as $value) {
    $shortPath[] = $nodeLabel[$value];
}
echo "The shortest path from {$nodeLabel[$from]} to {$nodeLabel[$to]} is: " . implode(' -> ', $shortPath) . "\n";
