import { createContext, useContext, useReducer } from 'react';
import { Me } from '@/types/Me';

export interface AppContextStateType {
  theme: string;
  setTheme: (theme: string) => void;
  user: Me | null;
  setUser: (user: Me | null) => void;
}

const initialState: AppContextStateType = {
  theme: 'dark',
  setTheme: () => {},
  user: null,
  setUser: () => {},
};

function appReducer(state: AppContextStateType, action: any) {
  switch (action.type) {
    case 'SET_THEME':
      return {
        ...state,
        theme: action.payload,
      };
    case 'SET_USER':
      return {
        ...state,
        user: action.payload,
      };
    default:
      return state;
  }
}

const AppContext = createContext(initialState);

export function AppProvider({ children }: { children: React.ReactNode }) {
  const [state, dispatch] = useReducer(appReducer, initialState);

  function setUser(user: AppContextStateType['user']) {
    dispatch({ type: 'SET_USER', payload: user });
  }

  function setTheme(theme: AppContextStateType['theme']) {
    dispatch({ type: 'SET_THEME', payload: theme });
  }

  return <AppContext.Provider value={{ ...state, setUser, setTheme }}>{children}</AppContext.Provider>;
}

// Create a custom hook to use the context
export function useAppContext() {
  return useContext(AppContext);
}
