module.exports = {
  trailingComma: 'all',
  tabWidth: 2,
  semi: true,
  singleQuote: true,
  printWidth: 120,
  bracketSpacing: true,
  importOrder: [
    '^react(.*)',
    '<THIRD_PARTY_MODULES>',
    '^(@/|@hooks/|@pages/|@components/|@layouts/|@utils/|@types/)',
    '^[./]',
    '^(.*).(s)*css',
  ],
  importOrderSortSpecifiers: true,
};
