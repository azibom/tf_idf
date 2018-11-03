<?php

class tf_idf
{
    // Declare  properties
    public $texts;

    /**
     * Method to explode the texts
     *
     * @param [array] $delimiters
     * @param [text] $string
     * @return array
     */
    public function multiExplode($delimiters, $string)
    {
        $mysentense = array();
        $setSentence = str_replace($delimiters, $delimiters[0], $string);
        $explodedSentence = explode($delimiters[0], $setSentence);

        return $explodedSentence;
    }

    /**
     * Method to sort the array
     *
     * @param [array] $my_array
     * @return array
     */
    public function sort($my_array)
    {
        for ($i = 0; $i < count($my_array); $i++) {
            $val = $my_array[$i][1];
            $word = $my_array[$i][0];
            $j = $i - 1;
            while ($j >= 0 && $my_array[$j][1] < $val) {
                $my_array[$j + 1][1] = $my_array[$j][1];
                $my_array[$j + 1][0] = $my_array[$j][0];
                $j--;
            }
            $my_array[$j + 1][1] = $val;
            $my_array[$j + 1][0] = $word;
        }
        return $my_array;
    }

    /**
     * Method to merge the arrays
     *
     * @param [array] $arrays
     * @return array
     */
    public function mergeArrays($arrays)
    {
        $myarray = $arrays[0];
        for ($i = 1; $i < count($arrays); $i++) {
            $myarray = array_merge($myarray, $arrays[$i]);
        }

        return $myarray;
    }

    /**
     * Method to filter the arrays
     *
     * @param [array] $arrays
     * @return array
     */
    public function deleteUnImportants($arrays)
    {
        $filters = $this->filter();
        for ($i = 0; $i < count($filters); $i++) {
            if (($key = array_search($filters[$i], $arrays)) !== false) {
                array_splice($arrays, $key, 1);
            }
        }

        return $arrays;
    }

    /**
     * Method to filter the arrays
     *
     * @param [array] $array
     * @return array
     */
    public function uniqueSorted2DArray($array)
    {
        $count = count($array);
        for ($i = 0; $i < $count; $i++) {
            for ($j = $i - 1; $j >= 0; $j--) {
                if (trim($array[$i][0]) == trim($array[$j][0])) {
                    array_splice($array, $i, 1);
                    $i--;
                    $count--;
                    break;
                }
            }
        }

        return $array;
    }

    /**
     * Method to difine the unimportant words
     *
     * @return array
     */
    public function filter()
    {
        $filter = array();

        // Prepositions and Conjunctions and etc
        $filter[] = array(
            'بین',
            'و',
            'از',
            'برای',
            'تا',
            'اما',
            'هر',
            'است',
            'شد',
            'گشت',
            'گردید',
        );

        return $this->mergeArrays($filter);
    }

    /**
     *  Method to calculate the tf
     *
     * @param [srting] $word
     * @param [array] $array
     * @return number
     */
    public function tf($word, $array)
    {
        $tf = 0;
        for ($z = 0; $z < count($array); $z++) {
            if ($word == $array[$z]) {
                $tf++;
            }
        }

        return $tf;
    }
        
    /**
     * Method to calculate the idf
     *
     * @param [string] $word
     * @param [number] $sentenceKey
     * @param [array] $arrays
     * @return number
     */
    public function idf($word, $sentenceKey, $arrays)
    {
        $idf = 0;
        for ($z = 0; $z < count($arrays); $z++) {
            if ($z != $sentenceKey) {
                for ($x = 0; $x < count($arrays[$z]); $x++) {
                    if ($word == $arrays[$z][$x]) {
                        $idf++;
                        break;
                    }
                }
            }
        }

        return $idf;
    }

    /**
     * Method to get the weight
     *
     * @param [number] $tf
     * @param [number] $idf
     * @param [number] $max
     * @param [number] $textCount
     * @return number
     */
    public function getWeight($tf, $idf, $max, $textCount)
    {
        $tf = 0.5 + (0.5 * $tf) / $max;
        $idf = log(($textCount / (1 + $idf)), 2);
        $weight = $idf * $tf;

        return $weight;
    }

    /**
     * Method to find the keywords
     *
     * @param [number] $sentenceKey
     * @return array
     */
    public function findKeyword($sentenceKey)
    {
        $texts = $this->texts;


        $tf = array();
        $idf = array();
        $newtexts = array();

        for ($i = 0; $i < count($texts); $i++) {
            $texts[$i] = $this->multiExplode(array(" ", ".", ":", "?", "،"), $texts[$i]);
            $texts[$i] = $this->deleteUnImportants($texts[$i]);
        }


        for ($j = 0; $j < count($texts[$sentenceKey]); $j++) {
            $word = $texts[$sentenceKey][$j];
            $tf[$j] = $this->tf($word, $texts[$sentenceKey]);
            $idf[$j] = $this->idf($word, $sentenceKey, $texts);
        }


        $max = max($tf);
        for ($j = 0; $j < count($texts[$sentenceKey]); $j++) {
            $weight = $this->getWeight($tf[$j], $idf[$j], $max, count($texts));
            $newtexts[$j] = array($texts[$sentenceKey][$j], $weight);
        }

        $newtexts = $this->sort($newtexts);
        $newtexts = $this->uniqueSorted2DArray($newtexts);
        return $newtexts;
    }

}
