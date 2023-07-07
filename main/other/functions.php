<?php

    function readJson($json) {
        $data = file_get_contents($json);
        $config = json_decode($data, true);

        return $config;
    }
    
    function detect_special_query($q)
    {
        // Code by pafefs
        $modified_query = str_replace(" ","",strtolower($q));
        if(strpos($modified_query,"my") !== false)
        {
            if(strpos($modified_query, "ip"))
            {
                return 1;
            }
            elseif(strpos($modified_query, "useragent") || strpos($modified_query, "user-agent") || strpos($modified_query, "ua"))
            {
                return 2;
            }
        }
        // Code by Avitld
        if (strpos($modified_query, "base64") !== false) {
            if (strpos($modified_query, "how") === false) {
                if (strpos($modified_query, "encode") !== false) {
                    return 3;
                } elseif (strpos($modified_query, "decode") !== false) {
                    return 4;
                }
            }
        }
    }
?>
