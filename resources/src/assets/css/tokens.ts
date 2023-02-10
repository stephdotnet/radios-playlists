type tokens = {
  colors: Record<string, string>;
};

export const tokens: tokens = {
  colors: {
    grey: '#CCCCCC',
    red: '#e30000',
    darkGrey: 'rgb(78, 78, 78)',
  },
};

const makeTokens = () => {
  const cssGenerated = Object.keys(tokens.colors).map((key) => {
    return `$${key}: ${tokens.colors[key]};`;
  });

  return cssGenerated.join('');
};

export default makeTokens;
