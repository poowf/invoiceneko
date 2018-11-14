<?php
if (defined('STDIN')) {
    $inputFileName = $argv[1];
    $outputFileName = $argv[2];
}

$directory = './tests/Results/';

$data = simplexml_load_file($inputFileName);

$json_string = json_encode($data);

$result_array = json_decode($json_string, true);

if(strpos($inputFileName, 'dusk') !== false)
{
    $mainResults = $result_array;
}
else
{
    $mainResults = $result_array['testsuite']['testsuite'];
}

$domCollection = flatten_xml_element($mainResults);

$outputFilePieces = explode(".", $outputFileName);
$outputFileNameOnly = $outputFilePieces[0];
$outputFileExtension = $outputFilePieces[1];

foreach($domCollection as $key => $domSingle)
{
    outputToFile($domSingle, $directory, $outputFileNameOnly . '-' . $key . '.' . $outputFileExtension);
}
//unlink($inputFileName);

function outputToFile($dom, $directory, $filename)
{
    if (!file_exists($directory)) {
        mkdir($directory, 0777, true);
    }
    $dom->encoding = 'UTF-8';
    $dom->formatOutput = true;
    $xml = $dom->saveXML();
    file_put_contents($directory . $filename, $xml);
}

function flatten_xml_element($dataArray)
{
    $domArray = [];

    foreach ($dataArray as $data) {
//        $element = $dom->createElement('testsuite', '');
//        // Add any @attributes
//        if (! empty($data['@attributes']) && is_array($data['@attributes'])) {
//            foreach ($data['@attributes'] as $attribute_key => $attribute_value) {
//                $element->setAttribute($attribute_key, $attribute_value);
//            }
//        }
//
//        $dom->appendChild($element);

        //Check for Single Element only, otherwise assume multiple elements
        if (array_key_exists('testsuite', $data) && array_key_exists('@attributes', $data['testsuite']) && array_key_exists('testcase', $data['testsuite'])) {
            $dom = new DOMDocument();
            $testsuite = $data['testsuite'];

            $element = $dom->createElement('testsuite', '');

            // Add any @attributes
            if (! empty($testsuite['@attributes']) && is_array($testsuite['@attributes'])) {
                foreach ($testsuite['@attributes'] as $attribute_key => $attribute_value) {
                    $element->setAttribute($attribute_key, $attribute_value);
                }
            }

            //Loop over internal testcases
            $testcase = $testsuite['testcase'];
            $childElement = $dom->createElement('testcase', '');

            if (! empty($testcase['@attributes']) && is_array($testcase['@attributes'])) {
                foreach ($testcase['@attributes'] as $attribute_key => $attribute_value) {
                    $childElement->setAttribute($attribute_key, $attribute_value);
                }
            }

            $element->appendChild($childElement);
            $dom->appendChild($element);

            array_push($domArray, $dom);

        } else {
            foreach ($data['testsuite'] as $testsuite) {
                $dom = new DOMDocument();
                $element = $dom->createElement('testsuite', '');

                // Add any @attributes
                if (! empty($testsuite['@attributes']) && is_array($testsuite['@attributes'])) {
                    foreach ($testsuite['@attributes'] as $attribute_key => $attribute_value) {
                        $element->setAttribute($attribute_key, $attribute_value);
                    }
                }

                //Loop over internal testcases
                //Check for Single Element only, otherwise assume multiple elements
                if (array_key_exists('testcase', $testsuite) && array_key_exists('@attributes', $testsuite['testcase'])) {
                    $testcase = $testsuite['testcase'];
                    $childElement = $dom->createElement('testcase', '');

                    if (! empty($testcase['@attributes']) && is_array($testcase['@attributes'])) {
                        foreach ($testcase['@attributes'] as $attribute_key => $attribute_value) {
                            $childElement->setAttribute($attribute_key, $attribute_value);
                        }
                    }

                    $element->appendChild($childElement);
                }
                else {
                    foreach ($testsuite['testcase'] as $testcase) {
                        $childElement = $dom->createElement('testcase', '');

                        if (! empty($testcase['@attributes']) && is_array($testcase['@attributes'])) {
                            foreach ($testcase['@attributes'] as $attribute_key => $attribute_value) {
                                $childElement->setAttribute($attribute_key, $attribute_value);
                            }
                        }

                        if(array_key_exists('failure', $testcase))
                        {
                            $toddlerElement = $dom->createElement('failure', $testcase['failure']);
                            $childElement->appendChild($toddlerElement);
                        }
                        elseif(array_key_exists('error', $testcase)) {
                            $toddlerElement = $dom->createElement('error', $testcase['error']);
                            $childElement->appendChild($toddlerElement);
                            print_r($toddlerElement);
                        }

                        $element->appendChild($childElement);
                    }
                }

                $dom->appendChild($element);
                array_push($domArray, $dom);
            }
        }
    }

    return $domArray;
}
?>