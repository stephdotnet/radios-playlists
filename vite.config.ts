import react from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';
import { defineConfig, loadEnv } from 'vite';
import makeTokens from './resources/src/assets/css/tokens';

const path = require('path');
let serverConfig = {};

export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, process.cwd(), 'VITE');
  if (env.VITE_HOST) {
    serverConfig = {
      server: {
        host: env.VITE_HOST,
        hmr: {
          host: env.VITE_HOST,
        },
      },
    };
  }

  return {
    plugins: [
      laravel({
        input: ['resources/src/index.tsx'],
        refresh: true,
      }),
      react(),
    ],
    resolve: {
      alias: {
        '@': path.resolve(__dirname, 'resources', 'src'),
        '@components': path.resolve(__dirname, 'resources', 'src', 'components'),
        '@css': path.resolve(__dirname, 'resources', 'src', 'assets', 'css'),
        '@layouts': path.resolve(__dirname, 'resources', 'src', 'layouts'),
        '@hooks': path.resolve(__dirname, 'resources', 'src', 'hooks'),
        '@pages': path.resolve(__dirname, 'resources', 'src', 'pages'),
        '@utils': path.resolve(__dirname, 'resources', 'src', 'utils'),
        '@types': path.resolve(__dirname, 'resources', 'src', 'utils'),
      },
    },
    server: {
      open: true,
    },
    css: {
      preprocessorOptions: {
        scss: {
          additionalData: makeTokens(),
        },
      },
    },
    ...serverConfig,
  };
});
