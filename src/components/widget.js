/** @format */

import React, { useState, useEffect } from "react";
import axios from "axios";
function Widget() {
  const [firstName, setFirstName] = useState("");
  const [lastName, setLastName] = useState("");
  const [email, setEmail] = useState("");
  const [loader, setLoader] = useState("Save Settings");
  const [message, setMessage] = useState("");

  const url = `${appLocalizer.apiUrl}/wprk/v1/settings`;

  const handleSubmit = (e) => {
    e.preventDefault();
    setLoader("Saving...");
    axios
      .post(
        url,
        {
          firstName: firstName,
          lastName: lastName,
          email: email,
        },
        {
          headers: {
            "content-type": "application/json",
            "X-WP-NONCE": appLocalizer.nonce,
          },
        }
      )
      .then((res) => {
        console.log("submitted");
        setLoader("Save Settings");
        setFirstName("");
        setLastName("");
        setEmail("");
        showSuccess();
      });
  };

  const showSuccess = () => {
    let successMessage = document.createElement("h1");
    successMessage.innerHTML = "Successfully saved settings!";
    successMessage.style.color = "green";
    document
      .getElementById("work-settings-form")
      .insertAdjacentElement("afterend", successMessage);

    setTimeout(() => successMessage.remove(), 2000);
  };

  return (
    <React.Fragment>
      <h2>React Settings Form</h2>
      <form id="work-settings-form" onSubmit={(e) => handleSubmit(e)}>
        <table className="form-table" role="presentation">
          <tbody>
            <tr>
              <th scope="row">
                <label htmlFor="firstName">FirstName</label>
              </th>
              <td>
                <input
                  id="firstName"
                  name="firstName"
                  value={firstName}
                  onChange={(e) => {
                    setFirstName(e.target.value);
                  }}
                  className="regular-text"
                />
              </td>
            </tr>
            <tr>
              <th scope="row">
                <label htmlFor="lastName">LastName</label>
              </th>
              <td>
                <input
                  id="lastName"
                  name="lastName"
                  value={lastName}
                  onChange={(e) => {
                    setLastName(e.target.value);
                  }}
                  className="regular-text"
                />
              </td>
            </tr>
            <tr>
              <th scope="row">
                <label htmlFor="email">Email</label>
              </th>
              <td>
                <input
                  id="email"
                  name="email"
                  value={email}
                  onChange={(e) => {
                    setEmail(e.target.value);
                  }}
                  className="regular-text"
                />
              </td>
            </tr>
          </tbody>
        </table>
        <p className="submit">
          <button type="submit" className="button button-primary">
            {loader}
          </button>
        </p>
      </form>
    </React.Fragment>
  );
}

export default Widget;
