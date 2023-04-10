import { useState } from 'react';
import useDebouncedCallback from './useDebouncedCallback';

export default function useDebouncedState<T>(
  initialValue: T,
  delay: number,
): [T, (newValue: T) => void] {
  const [state, setState] = useState<T>(initialValue);

  const debouncedSetState = useDebouncedCallback((newValue: T) => {
    setState(newValue);
  }, delay);

  return [state, debouncedSetState];
}
