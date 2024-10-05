import { useCallback, useEffect } from 'react';

export default function useDebouncedCallback<
  T extends (...args: unknown[]) => unknown,
>(callback: T, delay = 500): (...args: Parameters<T>) => void {
  let timerId: NodeJS.Timeout;

  const debouncedCallback = useCallback(
    (...args: Parameters<T>) => {
      clearTimeout(timerId);

      timerId = setTimeout(() => {
        callback(...args);
      }, delay);
    },
    [callback, delay],
  );

  useEffect(() => {
    return () => {
      clearTimeout(timerId);
    };
  }, []);

  return debouncedCallback;
}
