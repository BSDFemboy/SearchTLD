<?php
    function send_infobox($query) {
        $config = readJSON("/internalconfig.json");

        if (isset($_COOKIE["lang"])) {
			$lang = trim(htmlspecialchars($_COOKIE["lang"]));
		} else {
			$lang = "en";
		}

        $url = "$lang.wikipedia.org/w/api.php?action=query&exintro&explaintext&format=json&pithumbsize=500&prop=extracts|pageimages&redirects=1&titles=" . urlencode($query);
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        $pages = $data['query']['pages'];

        if (isset($data)) {
            foreach($pages as $page) {
                if (isset($page['pageid']) && $page['pageid'] > 0) {
                    $title = $page['title'];

                    if (isset($page['thumbnail'])) {
                        $thumbnailUrl = $page['thumbnail']['source'];
                    }
    
                    $description = substr($page['extract'], 0, 450);
                    $description = rtrim($description, " .,;:-");
                    if (strlen($page['extract']) > 450) {
                        $description .= "...";
                    }
                    
                    echo "<div class=\"infobox\">";
                    echo "<div class=\"txtholder\">";
                    echo "<h2 class=\"infotitle\"><a href=\"https://wikipedia.org/wiki/$query\">" . $title . "</a></h2>";
                    echo "</div>";
                    echo "<div class=\"simage-container\">";
                    echo "	<img src=\"$thumbnailUrl\">";
                    echo "</div>";
                    echo "<hr>";
                    echo "<p class=\"infodesc\">" . $description . "</p>";
                    echo "</div>";
                }
            }
        }
    
    }
?>

