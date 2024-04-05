const searchButton = document.getElementById("searchButton");
const clearButton = document.getElementById("clearButton");
let isSearchPerformed = false;

searchButton.addEventListener("click", async function () {
  const searchAccount = document.getElementById("searchAccount").value;

  if (searchAccount === "") {
    return;
  }

  clearButton.classList.remove("hidden");
  await fetch(`${apiLink}/fetchallusers?searchaccount=${searchAccount}`)
    .then((response) => response.json())
    .then((users) => updateTable(users));
});

clearButton.addEventListener("click", async function () {
  isSearchPerformed = false;
  document.getElementById("searchAccount").value = "";
  clearButton.classList.add("hidden");
  await fetch(`${apiLink}/fetchallusers`)
    .then((response) => response.json())
    .then((users) => updateTable(users));
});
