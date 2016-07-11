<?php

require_once 'ResistorGraph.php';
require_once 'GraphData.php';

header('Content-Type: text/xml;charset=UTF-8');

$nodeLabel = GraphData::getNodeLabelList();

// recieve xml string: <xml><pointA>0.0</pointA><pointB>1.0</pointB></xml>
$data = trim(file_get_contents('php://input'));
$xmlData = simplexml_load_string($data);

$pointA = isset($xmlData->pointA) ? $xmlData->pointA : null;
$pointB = isset($xmlData->pointB) ? $xmlData->pointB : null;

$dom = new DomDocument('1.0', 'UTF-8');
$response = $dom->appendChild($dom->createElement('response'));

if ($pointA === null || $pointB === null) {
    $response->appendChild($dom->createElement('error'))
        ->appendChild($dom->createTextNode('Point A or Point B are empty'));
    exit($dom->saveXML());
}
if (!in_array($pointA, $nodeLabel, null)) {
    $response->appendChild($dom->createElement('error'))
        ->appendChild($dom->createTextNode("Point {$pointA} doesn't exists in this graph"));
    exit($dom->saveXML());
}

if (!in_array($pointB, $nodeLabel, null)) {
    $response->appendChild($dom->createElement('error'))
        ->appendChild($dom->createTextNode("Point {$pointB} doesn't exists in this graph"));
    exit($dom->saveXML());
}

if ($pointA !== null && $pointB !== null) {
    $from = array_search($pointA, $nodeLabel, null);
    $to = array_search($pointB, $nodeLabel, null);
    $resistorGraph = new ResistorGraph(GraphData::getMatrix());
    $response->appendChild($dom->createElement('resistanceValue'))
        ->appendChild($dom->createTextNode($resistorGraph->getResistanceBetweenPoints($from, $to)));

    $shortPath = array();
    foreach ($resistorGraph->getShortestPath() as $value) {
        $shortPath[] = $nodeLabel[$value];
    }
    $response->appendChild($dom->createElement('shortestPath'))
        ->appendChild($dom->createTextNode(implode(' - ', $shortPath)));
}

echo $dom->saveXML();