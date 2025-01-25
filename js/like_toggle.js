document.addEventListener("DOMContentLoaded", function () {
  const favoritesForm = document.getElementById("favoritesForm");

  favoritesForm.addEventListener("submit", function (event) {
    event.preventDefault();

    const formData = new FormData(this);

    // Use AJAX to submit the form data
    fetch(this.action, {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success" && data.total_likes !== undefined && data.isFavorite !== undefined) {
          // Favorites updated successfully
          console.log(data)
          // console.log(data.message);
          // Update the likes count on the page
          updateFavoriteButton(data.isFavorite);
          updateLikesCount(data.total_likes);
        } else {
          // Handle errors or log a message
          console.error("Invalid response from the server");
        }
      })
      .catch((error) => {
        console.error("An error occurred:", error);
      });
  });
  
  function updateLikesCount(newCount) {
    const likesCountElement = document.getElementById("likes-count");

    if (likesCountElement) {
      likesCountElement.textContent = "Likes: " + newCount;
    }
  }
  function updateFavoriteButton(isFavorite) {
    const button = favoritesForm.querySelector(".add-to-favorites");
    const icon = button.querySelector("i");
    const span = button.querySelector("span");
    console.log(isFavorite)
    if (isFavorite) {
      icon.className = "bx bxs-like";
      span.textContent = "Dislike";
    } else {
      icon.className = "bx bx-like";
      span.textContent = "Like";
    }

    // Update the hidden input value
    favoritesForm.querySelector('input[name="isFavorite"]').value =
      isFavorite.toString();
  }
});
