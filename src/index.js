/** @format */
import React from "react";
import { ReactDOM } from "react";
import App from "./App";

document.addEventListener("DOMContentLoaded", () => {
  var element = document.getElementById("new-dashboard-widget");
  if (typeof element !== "undefined" && element !== null) {
    ReactDOM.render(<App />, document.getElementById("new-dashboard-widget"));
  }
});
