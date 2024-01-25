import requests
import argparse
import urllib3

# Suppress only the InsecureRequestWarning from urllib3 needed for disabling SSL warnings
urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)


def print_table(answers):
    max_len = max(len(str(answer)) for answer in answers)
    table_width = (max_len + 2) * 3 + 1  # Each cell has a width of (max_len + 2), and there are 3 cells

    horizontal_line = "┌" + "─" * (max_len + 2) + "┬" + "─" * (max_len + 2) + "┬" + "─" * (max_len + 2) + "┐"
    row_format = "│{:^" + str(max_len + 2) + "}│{:^" + str(max_len + 2) + "}│{:^" + str(max_len + 2) + "}│"
    separator_line = "├" + "─" * (max_len + 2) + "┼" + "─" * (max_len + 2) + "┼" + "─" * (max_len + 2) + "┤"
    bottom_line = "└" + "─" * (max_len + 2) + "┴" + "─" * (max_len + 2) + "┴" + "─" * (max_len + 2) + "┘"

    print(horizontal_line)
    print(row_format.format(*answers[:3]))
    print(separator_line)
    print(row_format.format(*answers[3:6]))
    print(separator_line)
    print(row_format.format(*answers[6:]))
    print(bottom_line)



def get_pokemon_with_minimum_aggregate(answers):
    # Get the first key in the dictionary
    first_key = next(iter(answers))
    minimum = answers[first_key]['aggCount']
    pokemon = answers[first_key]['pokemonId']

    for key, answer in answers.items():
        value = answer['aggCount']
        if value < minimum:
            minimum = value
            pokemon = answer['pokemonId']

    return pokemon



def get_pokedoku_stats(token, puzzle_id):
    url = f'https://api.pokedoku.com/api/puzzle/stats/{puzzle_id}'
    headers = {
        'Authority': 'api.pokedoku.com',
        'Accept': '*/*',
        'Accept-Language': 'it-IT,it;q=0.9,en-US;q=0.8,en;q=0.7',
        'Cookie': f'dont_show_helper=true; __Secure-next-auth.session-token={token}',
        'Origin': 'https://pokedoku.com',
        'Referer': 'https://pokedoku.com/',
        'Sec-Ch-Ua': '"Not_A Brand";v="8", "Chromium";v="120", "Google Chrome";v="120"',
        'Sec-Ch-Ua-Mobile': '?0',
        'Sec-Ch-Ua-Platform': '"Windows"',
        'Sec-Fetch-Dest': 'empty',
        'Sec-Fetch-Mode': 'cors',
        'Sec-Fetch-Site': 'same-site',
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    }

    response = requests.get(url, headers=headers, verify=False)
    return response.json()

def get_pokemon_name(id):
    url = f'https://pokeapi.co/api/v2/pokemon/{id}/'
    headers = {
        'Authority': 'pokeapi.co',
        'Accept': 'application/json,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'Accept-Language': 'it-IT,it;q=0.9,en-US;q=0.8,en;q=0.7',
        'Cache-Control': 'max-age=0',
        'Sec-Ch-Ua': '"Not_A Brand";v="8", "Chromium";v="120", "Google Chrome";v="120"',
        'Sec-Ch-Ua-Mobile': '?0',
        'Sec-Ch-Ua-Platform': '"Windows"',
        'Sec-Fetch-Dest': 'document',
        'Sec-Fetch-Mode': 'navigate',
        'Sec-Fetch-Site': 'none',
        'Sec-Fetch-User': '?1',
        'Upgrade-Insecure-Requests': '1',
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    }

    response = requests.get(url, headers=headers, verify=False)
    return response.json()['name']

def main():
    parser = argparse.ArgumentParser(description='Fetch and display Pokedoku puzzle stats.')
    parser.add_argument('puzzle_id', type=int, help='The ID of the puzzle')
    parser.add_argument('token', type=str, help='The authentication token')
    args = parser.parse_args()

    puzzle = get_pokedoku_stats(args.token, args.puzzle_id)

    table = []
    for slot, answers in puzzle['answerStats'].items():
        aggregates = answers['answerAggregates']
        pokemon_id = get_pokemon_with_minimum_aggregate(aggregates)
        pokemon_name = get_pokemon_name(pokemon_id)
        table.append(pokemon_name)

    print_table(table)

if __name__ == '__main__':
    main()
