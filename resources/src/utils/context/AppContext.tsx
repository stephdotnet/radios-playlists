import { createContext, useContext, useReducer } from 'react';
import { Me } from '@/types/Me';

export interface Alert {
  id: string;
  type: 'error' | 'warning' | 'info' | 'success';
  title: string;
}

export interface AppContextStateType {
  theme: string;
  setTheme: (theme: string) => void;
  user: Me | null;
  setUser: (user: Me | null) => void;
  alerts: Alert[];
  addAlert: (alert: Partial<Alert>) => void;
  dismissAlert: (id: Alert['id']) => void;
}

const initialState: AppContextStateType = {
  theme: 'dark',
  setTheme: () => {},
  user: null,
  setUser: () => {},
  alerts: [],
  addAlert: () => {},
  dismissAlert: () => {},
};

interface Action {
  type: string;
  payload: any;
}

function appReducer(state: AppContextStateType, action: Action) {
  switch (action.type) {
    case 'SET_THEME': {
      return {
        ...state,
        theme: action.payload,
      };
    }
    case 'SET_USER': {
      return {
        ...state,
        user: action.payload,
      };
    }
    case 'ADD_ALERT': {
      const newAlert = { id: Date.now(), ...action.payload };
      return {
        ...state,
        alerts: [...state.alerts, newAlert],
      };
    }
    case 'DISMISS_ALERT': {
      return {
        ...state,
        alerts: state.alerts.filter((alert) => alert.id !== action.payload),
      };
    }
    default: {
      return state;
    }
  }
}

const AppContext = createContext(initialState);

export function AppProvider({ children }: { children: React.ReactNode }) {
  const [state, dispatch] = useReducer(appReducer, initialState);

  const setUser = (user: AppContextStateType['user']) =>
    dispatch({ type: 'SET_USER', payload: user });

  const setTheme = (theme: AppContextStateType['theme']) =>
    dispatch({ type: 'SET_THEME', payload: theme });

  const addAlert = (alert: Partial<Alert>) =>
    dispatch({ type: 'ADD_ALERT', payload: alert });

  const dismissAlert = (id: Alert['id']) =>
    dispatch({ type: 'DISMISS_ALERT', payload: id });

  return (
    <AppContext.Provider
      value={{ ...state, setUser, setTheme, addAlert, dismissAlert }}
    >
      {children}
    </AppContext.Provider>
  );
}

// Create a custom hook to use the context
export function useAppContext() {
  return useContext(AppContext);
}
