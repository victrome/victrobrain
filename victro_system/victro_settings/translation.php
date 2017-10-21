<?php
/**
 * Translate phrase if it contains in files translations
 * @global type $victro_language var with translations
 * @param String $victro_string phrase
 * @param Int $victro_type Type of return 1 to just first upper, 2 to all upper, 3 to none upper
 * @param Boolean $victro_return true to return false to echo
 * @return String
 */
function victro_translate($victro_string, $victro_type = 1, $victro_return = false) {
    GLOBAL $victro_language;
    $victro_string3 = str_replace(" ", "_", $victro_string);
    $victro_string3 = mb_strtolower($victro_string3, "UTF-8");
    if (isset($victro_language[$victro_string3])) {
        $victro_newstring = $victro_language[$victro_string3];
    } else {
        $victro_string2 = explode(' ', $victro_string);
        $victro_i = 0;
        $victro_newstring1 = array();
        foreach ($victro_string2 as $victro_searchword) {
            $victro_searchword = mb_strtolower($victro_searchword, "UTF-8");
            if (isset($victro_string2[$victro_i - 1]) and isset($victro_string2[$victro_i]) and isset($victro_string2[$victro_i + 1])) {
                $victro_searchword1 = mb_strtolower($victro_string2[$victro_i - 1] . '_' . $victro_string2[$victro_i] . '_' . $victro_string2[$victro_i + 1], "UTF-8");
                if (isset($victro_language[$victro_searchword1])) {
                    $victro_newstring1[] = $victro_language[$victro_searchword1];
                    unset($victro_string2[$victro_i]);
                    unset($victro_string2[$victro_i - 1]);
                    unset($victro_string2[$victro_i + 1]);
                } else if (isset($victro_string2[$victro_i]) and isset($victro_string2[$victro_i + 1])) {
                    $victro_searchword3 = mb_strtolower($victro_string2[$victro_i] . '_' . $victro_string2[$victro_i + 1], "UTF-8");
                    if (isset($victro_language[$victro_searchword3])) {
                        $victro_newstring1[] = $victro_language[$victro_searchword3];
                        unset($victro_string2[$victro_i]);
                        unset($victro_string2[$victro_i + 1]);
                    } else if (isset($victro_string2[$victro_i]) and isset($victro_language[$victro_searchword])) {
                        $victro_newstring1[] = $victro_language[$victro_searchword];
                        unset($victro_string2[$victro_i]);
                    } else if (isset($victro_string2[$victro_i])) {
                        $victro_newstring1[] = $victro_searchword;
                        unset($victro_string2[$victro_i]);
                    }
                } else if (isset($victro_string2[$victro_i]) and isset($victro_language[$victro_searchword])) {
                    $victro_newstring1[] = $victro_language[$victro_searchword];
                    unset($victro_string2[$victro_i]);
                } else if (isset($victro_string2[$victro_i])) {
                    $victro_newstring1[] = $victro_searchword;
                    unset($victro_string2[$victro_i]);
                }
            } else if (isset($victro_string2[$victro_i]) and isset($victro_string2[$victro_i + 1])) {
                $victro_searchword3 = mb_strtolower($victro_string2[$victro_i] . '_' . $victro_string2[$victro_i + 1], "UTF-8");
                if (isset($victro_language[$victro_searchword3])) {
                    $victro_newstring1[] = $victro_language[$victro_searchword3];
                    unset($victro_string2[$victro_i]);
                    unset($victro_string2[$victro_i + 1]);
                } else if (isset($victro_string2[$victro_i]) and isset($victro_language[$victro_searchword])) {
                    $victro_newstring1[] = $victro_language[$victro_searchword];
                    unset($victro_string2[$victro_i]);
                } else if (isset($victro_string2[$victro_i])) {
                    $victro_newstring1[] = $victro_searchword;
                    unset($victro_string2[$victro_i]);
                }
            } else if (isset($victro_string2[$victro_i]) and isset($victro_language[$victro_searchword])) {
                $victro_newstring1[] = $victro_language[$victro_searchword];
                unset($victro_string2[$victro_i]);
            } else if (isset($victro_string2[$victro_i])) {
                $victro_newstring1[] = $victro_searchword;
                unset($victro_string2[$victro_i]);
            }
            $victro_i++;
        }
        $victro_newstring = implode(' ', $victro_newstring1);
    }
    if ($victro_type == 2) {
        $victro_newstring = mb_strtoupper($victro_newstring, "UTF-8");
    } else if ($victro_type == 3) {
        $victro_newstring = mb_strtolower($victro_newstring, "UTF-8");
    } else if ($victro_type == 1) {
        $victro_newstring = ucfirst($victro_newstring);
    } else {
        $victro_newstring = mb_strtolower($victro_newstring, "UTF-8");
    }
    if ($victro_return == false) {
        echo $victro_newstring;
    } else {
        return($victro_newstring);
    }
}

?>