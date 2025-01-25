window.addEventListener("DOMContentLoaded", (event) => {
  const searchInput = document.getElementById("search-input");
  const noResultsIcon = document.querySelector(".no-results i");
  const searchBox = document.querySelector(".search-box");

  noResultsIcon.addEventListener("click", function () {
    searchBox.classList.toggle("open");
    if (searchBox.classList.contains("open")) {
      searchInput.focus();
      searchInput.setSelectionRange(
        searchInput.value.length,
        searchInput.value.length
      );
    }
  });
});
