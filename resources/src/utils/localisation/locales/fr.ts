const pages = {
  'pages.playlists.title': 'Playlists',
  'pages.playlist.go_back_to_playlists': 'Retour aux playlists',
  'pages.playlist_detail.login_info':
    'Se connecter pour synchroniser la playlist',
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
