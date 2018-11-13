<?php
if (defined('STDIN')) {
    $inputFileName = $argv[1];
    $outputFileName = $argv[2];
}

$data = simplexml_load_file($inputFileName);

$json_string = json_encode($data);

$result_array = json_decode($json_string, true);

$mainResults = $result_array['testsuite']['testsuite'];

$doc = new DOMDocument();
$doc->encoding = 'UTF-8';
$dom = flatten_xml_element($doc, $mainResults);
$dom->formatOutput = true;
$xml = $doc->saveXML();

file_put_contents($outputFileName, $xml);
unlink($inputFileName);

function flatten_xml_element($dom, $dataArray)
{
    foreach ($dataArray as $data) {
        $element = $dom->createElement('testsuite', '');
        // Add any @attributes
        if (! empty($data['@attributes']) && is_array($data['@attributes'])) {
            foreach ($data['@attributes'] as $attribute_key => $attribute_value) {
                $element->setAttribute($attribute_key, $attribute_value);
            }
        }

        $dom->appendChild($element);

        //Check for Single Element only, otherwise assume multiple elements
        if (array_key_exists('@attributes', $data['testsuite']) && array_key_exists('testcase', $data['testsuite'])) {
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
        } else {
            foreach ($data['testsuite'] as $testsuite) {
                $element = $dom->createElement('testsuite', '');

                // Add any @attributes
                if (! empty($testsuite['@attributes']) && is_array($testsuite['@attributes'])) {
                    foreach ($testsuite['@attributes'] as $attribute_key => $attribute_value) {
                        $element->setAttribute($attribute_key, $attribute_value);
                    }
                }

                //Loop over internal testcases
                foreach ($testsuite['testcase'] as $testcase) {
                    $childElement = $dom->createElement('testcase', '');

                    if (! empty($testcase['@attributes']) && is_array($testcase['@attributes'])) {
                        foreach ($testcase['@attributes'] as $attribute_key => $attribute_value) {
                            $childElement->setAttribute($attribute_key, $attribute_value);
                        }
                    }

                    $element->appendChild($childElement);
                }

                $dom->appendChild($element);
            }
        }
    }

    return $dom;
}
?>