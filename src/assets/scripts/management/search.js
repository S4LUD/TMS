const searchButton = document.getElementById("searchButton");
const clearButton = document.getElementById("clearButton");
let isSearchPerformed = false;

searchButton.addEventListener("click", function () {
  const searchAccount = document.getElementById("searchAccount").value;

  if (searchAccount === "") {
    return;
  }

  clearButton.classList.remove("hidden");
  fetch(`https://tms-project.000webhostapp.com/api/fetchallusers?searchaccount=${searchAccount}`)
    .then((response) => response.json())
    .then((users) => updateTable(users));
});

clearButton.addEventListener("click", function () {
  isSearchPerformed = false;
  document.getElementById("searchAccount").value = "";
  clearButton.classList.add("hidden");
  fetch(`https://tms-project.000webhostapp.com/api/fetchallusers`)
    .then((response) => response.json())
    .then((users) => updateTable(users));
});
