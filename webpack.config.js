/** @format */

module.exports = {
  entry: "./src/index.js",
  output: {
    path: __dirname,
    filename: "./dist/bundle.js",
  },
  module: {
    rules: [
      {
        test: /\.jsx?$/,
        use: {
          loader: "babel-loader",
          options: {
            presets: ["@babel/preset-env", "@babel/preset-react"],
            plugins: ["@babel/plugin-proposal-class-properties"],
          },
        },
        exclude: /node_modules/,
      },
    ],
  },
};
