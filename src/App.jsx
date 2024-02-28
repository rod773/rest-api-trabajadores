import React from "react";

import ReactDOM from "react-dom";

import { App } from "./App";

const containers = document.querySelectorAll(".react-plugin");

containers.forEach((container) => {
  ReactDOM.render(<App />, container);
});
