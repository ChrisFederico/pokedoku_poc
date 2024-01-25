# Pokedoku Puzzle Stats Exploit

This PHP script interacts with the Pokedoku API to retrieve puzzle statistics and display a 3x3 table of Pokémon names based on the obtained data.

## Usage

### PHP

Replace the placeholder token in the script with a valid authentication token. Ensure that you have the necessary permissions to access the Pokedoku API.

```php
$token = 'YOUR_AUTH_TOKEN_HERE';
```

clone the repository and paste it in your MAMP directory (mine was C:\MAMP\htdocs) or in your local server, then navigate with your browser at the page

```
https://localhost/pokedoku_poc
```

### Python

If you want to run the python script you can type the following command in your terminal:

```python
python script_name.py puzzle_id auth_token
```

Execute the script, and it will fetch puzzle statistics and display a 3x3 table of Pokémon names with minimum aggregate counts.

## Script Structure

1. get_pokedoku_stats($token)
This function sends a GET request to the Pokedoku API to retrieve puzzle statistics for a specific puzzle ID (in this case, ID 173).

2. get_pokemon_with_minimum_aggregate($answers)
This function finds the Pokémon with the minimum aggregate count from the answer statistics obtained from the Pokedoku API.

3. get_pokemon_name($id)
This function retrieves the Pokémon name from the PokeAPI using the Pokémon ID.

4. print_table($answers)
This function takes an array of Pokémon names and prints them in a 3x3 table format using HTML.

5. Main Logic
The script retrieves puzzle statistics, identifies the Pokémon with the minimum aggregate count for each slot, gets the Pokémon names, and prints them in a 3x3 table.

## Important Note
This script includes some SSL verification bypassing for testing purposes (CURLOPT_SSL_VERIFYHOST and CURLOPT_SSL_VERIFYPEER set to 0). In a production environment, it's crucial to enable SSL checks.

Ensure that you have the required permissions to access the Pokedoku API.

Review and modify the headers and other parameters if needed, especially if the Pokedoku API or PokeAPI endpoints change.

## Disclaimer

This script is provided for educational and illustrative purposes. Use it responsibly and respect the terms of service of the APIs involved.




