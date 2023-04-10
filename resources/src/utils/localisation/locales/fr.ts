const pages = {
  'pages.playlists.title': 'Playlists',
  'pages.playlist.go_back_to_playlists': 'Retour aux playlists',
  'pages.playlist_detail.login_info':
    'Se connecter pour synchroniser la playlist',
  'pages.playlist_detail.sync.error':
    'Erreur de synchronisation de la playlist',
  'pages.playlist_detail.sync.summary.success':
    'La playlist a été synchronisée avec succès.',
  'pages.playlist_detail.sync.summary.success_and_creation':
    'La playlist a été créé et synchronisée avec succès.',
  'pages.playlist_detail.sync.summary.chip.label':
    '{{count}} chansons synchronisées',
  'pages.playlist_detail.sync.playlist.spotify_url':
    'Voir la playlist sur Spotify',
  'pages.playlist_detail.songs.remove_song_title':
    'Supprimer la chanson de la playlist ?',
  'pages.playlist_detail.playlist.sync_button': 'Synchoniser la playlist',
  'pages.playlist_detail.playlist.open_in_spotify': 'Voir sur spotify',
  'pages.playlist_detail.song.card.added_on': 'Ajouté le {{ date }}',
  'pages.playlist_detail.search.label': 'Chercher un titre',
};

const components = {
  'auth.error':
    "Une erreur s'est produite lors de la récupération de votre compte",
  'auth.login.button_text': 'Se connecter à Spotify',
  'auth.greeting': 'Bonjour {{name}} !',
  'auth.logout': 'Se déconnecter',
};

const system = {
  'system.app.title': 'Radios Playlists',
  'system.actions.delete': 'Supprimer',
};

export default {
  ...components,
  ...pages,
  ...system,
};
