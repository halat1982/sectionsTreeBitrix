<?php

namespace ItTower\Helpers;

use \Exception;

class SectionHelper
{
    public static function getSectionsTree(array $sections): array
    {
        if (empty($sections)) {
            return array();
        }

        try {
            foreach ($sections as $section) {
                $depthArray[$section["DEPTH_LEVEL"]] = $section["DEPTH_LEVEL"];
                $copySections[$section["ID"]] = $section;
            }

            rsort($depthArray);

            $maxDepthLevel = $depthArray[0];

            for ($i = $maxDepthLevel; $i > 1; $i--) {
                foreach ($copySections as $sectionID => $section) {
                    if ($section['DEPTH_LEVEL'] == $i) {
                        $copySections[$section['IBLOCK_SECTION_ID']]['CHILDRENS'][$section["ID"]] = $section;
                        unset($copySections[$sectionID]);
                    }
                }
            }


            usort($copySections, "self::sortRules");


        } catch (Exception $e) {
            Logger::varDumpLog($e->getMessage());
        }

        $treeSections = array();
        foreach ($copySections as $section){
            $treeSections[$section['ID']] = $section;
        }

        return $treeSections;
    }

    static function sortRules($a, $b)
    {
        return ($a['SORT'] <=> $b['SORT']);
    }
}
