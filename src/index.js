/** @format */

import React from "react";
import ReactDOM from "react-dom";
import App from "./App";

document.addEventListener("DOMContentLoaded", () => {
  var element = document.getElementById("wp_react_kickoff_new_plugin");
  if (typeof element !== "undefined" && element !== null) {
    ReactDOM.render(
      <App />,
      document.getElementById("wp_react_kickoff_new_plugin")
    );
    console.log("Hello");
  }
});
