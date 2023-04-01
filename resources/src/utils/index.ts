/* eslint-disable @typescript-eslint/no-explicit-any */
export type Haystack = undefined | null | any[] | Record<string, any>;

export const env = (key: string) => {
  return dataGetValue(import.meta.env, key);
};

/* eslint-disable @typescript-eslint/no-explicit-any */
export function dataGetValue(
  object: Haystack,
  key: string | number,
  defaultValue: any = null,
) {
  try {
    if (typeof object === 'undefined' || object === null) {
      return defaultValue;
    }

    const resolved = key
      .toString()
      .split('.')
      /* eslint-disable @typescript-eslint/no-explicit-any */
      .reduce(function (prev: { [x: string]: any }, curr: string | number) {
        return prev ? prev[curr] : defaultValue;
      }, object);

    return typeof resolved !== 'undefined' ? resolved : defaultValue;
  } catch (error) {
    return defaultValue;
  }
}
