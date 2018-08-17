<?php
namespace ahvla\entity\submission;

/**
 * Class SeparateIsolatesDecorator
 *
 * Alters the results from the submissions to reorder the data by isolates if
 * applicable
 * @package ahvla\entity\submission
 */
class SeparateIsolatesDecorator {
    /**
     * Separates the section data by isolate "reference"
     *
     * @param array $results
     * @return array
     */
    public function separateIsolates(array $results) {
        // loop through the sections
        foreach ($results['Sections'] as $key => $section) {
            if (isset($section['Isolates']) && !empty($section['Isolates'])) {
                $results['Sections'][$key] = $this->separateData($section);
            }
        }

        return $results;
    }

    /**
     * Separates the isolates data from the rest of the data
     *
     * @param array $section
     * @return array
     */
    private function separateData(array $section) {
        // retrieve references
        $references = $this->getReferencesFromIsolates($section['Isolates']);

        // loop through the references and retrieve the data by reference
        $section['SeparateIsolates'] = [];
        foreach ($references as $reference) {
            $section['SeparateIsolates'][$reference] = [
                'Isolates' => $this->getIsolatesByReference($section['Isolates'], $reference),
                'SubSections' => $this->getSubSectionsByReference($section['SubSections'], $reference),
            ];
        }

        return $section;
    }

    /**
     * Retrieves the unique references available from the isolates
     *
     * @param array $data The isolate data to retrieve references from
     * @return array
     */
    private function getReferencesFromIsolates(array $data) {
        $references = [];
        foreach ($data as $isolate) {
            $references[$isolate['Reference']] = true;
        }

        return array_keys($references);
    }

    /**
     * Gets only the isolates with the given reference
     *
     * @param array $isolates A list of isolates
     * @param string $reference The reference to limit the isolates by
     * @return array
     */
    private function getIsolatesByReference(array $isolates, $reference) {
        $data = [];
        foreach ($isolates as $isolate) {
            if ($isolate['Reference'] == $reference) {
                $data[] = $isolate;
            }
        }

        return $data;
    }

    /**
     * Gets only the subsections with results that match the given reference
     *
     * @param array $subSections A list of subsections containing results
     * @param string $reference The reference to limit the subsections by
     * @return array
     */
    private function getSubSectionsByReference(array $subSections, $reference) {
        // loops through the subsections
        foreach ($subSections as $key => $subSection) {
            // limit the results to only those with the correct reference
            $results = [];
            foreach ($subSection['Results'] as $resultKey => $result) {
                if ($result['SampleName'] == $reference) {
                    $results[] = $result;
                }
            }
            $subSections[$key]['Results'] = $results;

            // unset subsections if no results
            if (!$results) {
                unset($subSections[$key]);
            }
        }

        return $subSections;
    }
}