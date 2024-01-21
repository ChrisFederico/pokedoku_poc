<?php

// get the token from the headers of the request and replace the following string
$token = 'eyJhbGciOiJIUzUxMiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiI0NDY2MDRkMy04M2NkLTRhZDQtODNhMy01Yzc1OTk1NDA1YmUiLCJ0ZW1wIjp0cnVlLCJlbWFpbCI6bnVsbCwicm9sZSI6IlRFTVAiLCJpYXQiOjE3MDU4NzE0NzEsImV4cCI6MTcwODQ2MzQ3MX0.ZG0J0sztOEreEl7EDpmYnOqD8TI0__hIymRrRDWNP3-kZqct4Yk0aSOdb1tHMBXzXij08Ax9ghGb0ypG-w6I-Q';

$puzzle = get_pokedoku_stats($token);

foreach($puzzle->answerStats as $slot => $answers) {
    $aggregates = $answers->answerAggregates;
    $pokemon_id = get_pokemon_with_minimum_aggregate($aggregates);
    $pokemon_name = get_pokemon_name($pokemon_id);
    $table[] = $pokemon_name;
}

print_table($table);

function print_table($answers) {
    echo "<table border='1'>";
    $matrix = array_chunk($answers, 3);
    foreach ($matrix as $row) {
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>$value</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

function get_pokemon_with_minimum_aggregate($answers) {
    $minimum = reset($answers)->aggCount;
    $pokemon = reset($answers)->pokemonId;
        
    foreach($answers as $answer) {
        $value = $answer->aggCount;
        if($value < $minimum) {
            $minimum = $value;
            $pokemon = $answer->pokemonId;
        }
    }

    return $pokemon;
}

function get_pokedoku_stats($token) {    
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://api.pokedoku.com/api/puzzle/stats/173');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    // WARNING: do not disable SSL checks in production! This is for testing purposes only!
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

    $headers = array();
    $headers[] = 'Authority: api.pokedoku.com';
    $headers[] = 'Accept: */*';
    $headers[] = 'Accept-Language: it-IT,it;q=0.9,en-US;q=0.8,en;q=0.7';
    $headers[] = 'Cookie: dont_show_helper=true; __Secure-next-auth.session-token=' . $token;
    $headers[] = 'Origin: https://pokedoku.com';
    $headers[] = 'Referer: https://pokedoku.com/';
    $headers[] = 'Sec-Ch-Ua: \"Not_A Brand\";v=\"8\", \"Chromium\";v=\"120\", \"Google Chrome\";v=\"120\"';
    $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
    $headers[] = 'Sec-Ch-Ua-Platform: \"Windows\"';
    $headers[] = 'Sec-Fetch-Dest: empty';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Sec-Fetch-Site: same-site';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }

    curl_close($ch);

    return json_decode($result);
}

function get_pokemon_name($id) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://pokeapi.co/api/v2/pokemon/' . $id . '/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    // WARNING: do not disable SSL checks in production! This is for testing purposes only!
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

    $headers = array();
    $headers[] = 'Authority: pokeapi.co';
    $headers[] = 'Accept: application/json,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7';
    $headers[] = 'Accept-Language: it-IT,it;q=0.9,en-US;q=0.8,en;q=0.7';
    $headers[] = 'Cache-Control: max-age=0';
    $headers[] = 'If-None-Match: W/\"3ca87-8kJ0Z2yPgAgkRaW0B+JKwpVxUmU\"';
    $headers[] = 'Sec-Ch-Ua: \"Not_A Brand\";v=\"8\", \"Chromium\";v=\"120\", \"Google Chrome\";v=\"120\"';
    $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
    $headers[] = 'Sec-Ch-Ua-Platform: \"Windows\"';
    $headers[] = 'Sec-Fetch-Dest: document';
    $headers[] = 'Sec-Fetch-Mode: navigate';
    $headers[] = 'Sec-Fetch-Site: none';
    $headers[] = 'Sec-Fetch-User: ?1';
    $headers[] = 'Upgrade-Insecure-Requests: 1';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }

    curl_close($ch);

    return json_decode($result)->name;
}
