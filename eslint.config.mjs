export default [
  {
    languageOptions: {
      ecmaVersion: 2020,
      sourceType: "script",
      globals: {
        window: "readonly",
        document: "readonly",
        setTimeout: "readonly",
        setInterval: "readonly",
        clearInterval: "readonly",
        fetch: "readonly",
        FormData: "readonly",
        console: "readonly",
        IntersectionObserver: "readonly",
        parseInt: "readonly",
        localStorage: "readonly",
        navigator: "readonly",
        URLSearchParams: "readonly",
      }
    },
    rules: {
      "no-undef": 2,
      "no-unused-vars": 1,
    }
  }
];
