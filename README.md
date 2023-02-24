# Auto playlists
[![codecov](https://codecov.io/gh/stephdotnet/radios-playlists/branch/main/graph/badge.svg?token=678815278E)](https://codecov.io/gh/stephdotnet/radios-playlists)

This app stands as a command to parse periodically a source (eg: a radio website) in order to extract the current song being played. Once the information retrieved, it will add the song to a spotify playlist.

## Installation
- Create `.env` from the `.env.example` file and set the value with your infos
- Run `php artisan key:generate`

## Structure
- Parse command
  - From a list of sources, run the extraction service that will pull and push a playlist
- Parser service
  - Extracts the currently played music on the given website
- Spotify service
  - Search a song 
  - Add a song to a playlist

## Testing
Run `php artisan test`

## License
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
