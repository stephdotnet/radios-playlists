import { useMemo } from 'react';

export function useHumanReadableFileSize(size: number): string {
  return useMemo(() => {
    const sizeExtensions = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    let i = 0;
    while (size >= 1024 && i < sizeExtensions.length - 1) {
      size /= 1024;
      i++;
    }

    return `${size.toFixed(2)} ${sizeExtensions[i]}`;
  }, [size]);
}
