# Auto playlists

![radios playlists logo](./public/images/github.jpg)

[![codecov](https://codecov.io/gh/stephdotnet/radios-playlists/branch/main/graph/badge.svg?token=678815278E)](https://codecov.io/gh/stephdotnet/radios-playlists)

This app stands as a backend parsing periodically a source (eg: a radio website) in order to extract the current song being played. It also comes with a React frontend app to list and sync the playlist with spotify.

**2 playlists** are currently available on [www.radios.creasteph.net](https://www.radios.creasteph.net) : Nostalgie & Swissjazz

**Motivations** : I wanted to work on a side project to mix 2 of my passions : music and coding. With this side project i can also work and explore some advanced practices that are required in my job as a full stack web-developer.

The tools and practices i used and implemented :
- React & Typescript
  - Tanstack Query & Axios Api Service
  - Material UI
  - Vite
- Laravel
  - Vite, HMR plugin & React/TS embed stack
  - Stateful Spotify Authentication
  - Api Resources & Query Builder
  - Full test coverage & Third party service mocking
- CI/CD
  - Test checks
  - Automatic semantic releases
  - Automatic deployment

## Installation
- Create `.env` from the `.env.example` file and set the value with your infos
- Run `php artisan key:generate`

## Structure
- Parse command
  - From a list of sources, run the extraction service that will pull a song and push on a playlist
- Parser service
  - Extracts the currently played music on the given website
- Spotify service
  - Search a song 
  - Add a song to a playlist
  - Sync playlist with origin

## Testing
Run `php artisan test`

## License
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
