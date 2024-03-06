/** @format */

module.exports = {
  entry: "./src/index.js",
  output: {
    path: __dirname,
    filename: "./dist/bundle.js",
  },
  mode: "development",
  module: {
    rules: [
      {
        test: /.js$/,
        loader: "babel-loader",
        exclude: /node_modules/,
        options: {
          presets: [["env", "react"]],
          plugins: ["transform-class-properties"],
        },
      },
    ],
  },
};
