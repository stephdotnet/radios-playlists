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
};

const components = {
  'auth.error':
    "Une erreur s'est produite lors de la récupération de votre compte",
  'auth.login.button_text': 'Se connecter à Spotify',
  'auth.greeting': 'Bonjour {{name}} !',
  'auth.logout': 'Se déconnecter',
};

const system = {
  'system.app.title': 'Radio playlist',
};

export default {
  ...components,
  ...pages,
  ...system,
};
