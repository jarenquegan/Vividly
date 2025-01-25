document.addEventListener("DOMContentLoaded", function () {
  const followForm = document.getElementById("followForm");

  followForm.addEventListener("submit", function (event) {
      event.preventDefault();

      const formData = new FormData(this);

      // Use AJAX to submit the form data
      fetch(this.action, {
          method: "POST",
          body: formData,
      })
      .then((response) => response.text()) // Get raw response text
      .then((rawResponse) => {
          console.log("Raw Response Data:", rawResponse);

          try {
              // Attempt to parse the response as JSON
              const data = JSON.parse(rawResponse);

              if (data.status === "success" && data.total_followers !== undefined && data.isFollowing !== undefined) {
                  // Follow status updated successfully
                  console.log(data);
                  // console.log(data.message);
                  // Update the followers count on the page
                  updateFollowButton(data.isFollowing);
                  updateFollowersCount(data.total_followers);
              } else {
                  // Handle errors or log a message
                  console.error("Invalid response from the server");
              }
          } catch (error) {
              console.error("Error parsing JSON:", error);
          }
      })
      .catch((error) => {
          console.error("An error occurred:", error);
      });
  });

  function updateFollowersCount(newCount) {
      const followersCountElement = document.getElementById("followers-count");

      if (followersCountElement) {
          followersCountElement.innerHTML = '<i class="bx bxs-heart"></i> ' + newCount;
      }
  }

  function updateFollowButton(isFollowing) {
      const button = followForm.querySelector(".follow-artist");
      const icon = button.querySelector("i");
      const span = button.querySelector("span");

      if (isFollowing) {
          icon.className = "bx bxs-heart";
          span.textContent = "Unfollow";
      } else {
          icon.className = "bx bx-heart";
          span.textContent = "Follow";
      }

      // Update the hidden input value
      followForm.querySelector('input[name="isFollowing"]').value = isFollowing.toString();
  }
});
