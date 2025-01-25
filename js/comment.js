document.addEventListener("DOMContentLoaded", function () {
  const commentForm = document.getElementById("commentForm");
  const commentsContainer = document.getElementById("comments-container");
  const headingElement = document.getElementById("comments-heading");

  commentForm.addEventListener("submit", function (event) {
    event.preventDefault();

    const formData = new FormData(commentForm);

    fetch(commentForm.action, {
      method: commentForm.method,
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        console.log("Response Data:", data);

        if (data.success) {
          // Assuming your server responds with the new comment data
          const newComment = data.comment;
          appendComment(newComment);
          commentForm.reset();
        } else {
          console.error("Comment submission failed.");
        }
      })
      .catch((error) => {
        console.error("An error occurred:", error);
      });
  });

  function appendComment(comment) {
    let hrefValueDelete = `delete.php?comment_id=${comment.comment_id}&confirm=true&comment=true`;
    
    // Check if the current artist can delete the comment
    const canDelete = comment.artist_id === currentArtistId || isAssociated;

    const newCommentElement = document.createElement("li");
    newCommentElement.innerHTML = `
              <div class="artist-item" style="border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 15px;">
                <div class="artist_pic">
                  <img src="images/${comment.artist_pic}" loading="lazy" alt="${
      comment.username
    }">
                </div>
                <div class="artist-details">
                  <span style="display: flex; align-items: center;">
                    <span class="spanspan">
                      <h3>${getArtistName(comment)}</h3>
                    </span>
                    <span>
                      <p>${formatTimeAgo(
                        comment.created_at
                      )} <i class='bx bx-time'></i></p>
                    </span>
                  </span>
                  <p style="display: flex; align-items: center;">
                    ${comment.comment_text}
                  </p>
                  <p><i class='bx bx-calendar-alt'></i> ${formatDate(
                    comment.created_at
                  )}</p>
                  ${canDelete ?
                    `<a href="${hrefValueDelete}" style="text-decoration: none; color: inherit;">
                      <p style="text-align: right;" class="dashspan">
                        Delete <i class='bx bx-trash'></i>
                      </p>
                    </a>` : ''
                  }
                </div>
              </div>
            `;
    commentsContainer.insertBefore(
      newCommentElement,
      commentsContainer.firstChild
    );
  }

  function updateComments(comments) {    
    if (Array.isArray(comments)) {
      const commentList = comments
        .map((comment) => {
          if (comment &&
            comment.comment_id && // Check if comment_id is defined
            comment.artist_pic &&
            comment.username &&
            comment.created_at &&
            comment.comment_text) {
            let hrefValueDelete = `delete.php?comment_id=${comment.comment_id}&confirm=true&comment=true`;
            // Check if the current artist can delete the comment
            const canDelete = comment.artist_id === currentArtistId || isAssociated;

            return `
              <li>
                <div class="artist-item" style="border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 15px;">
                  <div class="artist_pic">
                    <img src="images/${
                      comment.artist_pic
                    }" loading="lazy" alt="${comment.username}">
                  </div>
                  <div class="artist-details">
                    <span style="display: flex; align-items: center;">
                      <span class="spanspan">
                        <h3>${getArtistName(comment)}</h3>
                      </span>
                      <span>
                        <p>${formatTimeAgo(
                          comment.created_at
                        )} <i class='bx bx-time'></i></p>
                      </span>
                    </span>
                    <p style="display: flex; align-items: center;">
                      ${comment.comment_text}
                    </p>
                    <p><i class='bx bx-calendar-alt'></i> ${formatDate(
                      comment.created_at
                    )}</p>
                    ${canDelete ?
                      `<a href="${hrefValueDelete}" style="text-decoration: none; color: inherit;">
                        <p style="text-align: right;" class="dashspan">
                          Delete <i class='bx bx-trash'></i>
                        </p>
                      </a>` : ''
                    }
                  </div>
                </div>
              </li>
            `;
          } else {
            // Log an error if the comment object or its properties are missing
            console.error("Invalid comment object:", comment);
            return ""; // Return an empty string for the invalid comment
          }
        })
        .join("");
      commentsContainer.innerHTML = `
        <ul class="artist-list">${commentList}</ul>
        <div style="border-top: 0px solid #161616; padding-bottom: 10px;"></div>
      `;
    } else if (typeof comments === "object" && comments !== null) {
      if (
        comments.comment_id && // Check if comment_id is defined
        comments.artist_pic &&
        comments.username &&
        comments.created_at &&
        comments.comment_text) {
        let hrefValueDelete = `delete.php?comment_id=${comments.comment_id}&confirm=true&comment=true`;
        // Check if the current artist can delete the comment
        const canDelete = comment.artist_id === currentArtistId || isAssociated;
        const commentList = `
          <li>
            <div class="artist-item" style="border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 15px;">
              <div class="artist_pic">
                <img src="images/${comments.artist_pic}" loading="lazy" alt="${
          comments.username
        }">
              </div>
              <div class="artist-details">
                <span style="display: flex; align-items: center;">
                  <span class="spanspan">
                    <h3>${getArtistName(comments)}</h3>
                  </span>
                  <span>
                    <p>${formatTimeAgo(
                      comments.created_at
                    )} <i class='bx bx-time'></i></p>
                  </span>
                </span>
                <p style="display: flex; align-items: center;">
                  ${comments.comment_text}
                </p>
                <p><i class='bx bx-calendar-alt'></i> ${formatDate(
                  comments.created_at
                )}</p>
                ${canDelete ?
                  `<a href="${hrefValueDelete}" style="text-decoration: none; color: inherit;">
                    <p style="text-align: right;" class="dashspan">
                      Delete <i class='bx bx-trash'></i>
                    </p>
                  </a>` : ''
                }
              </div>
            </div>
          </li>
        `;
        commentsContainer.innerHTML = `
          <ul class="artist-list">${commentList}</ul>
          <div style="border-top: 0px solid #161616; padding-bottom: 10px;"></div>
        `;
      } else {
        // Log an error if the comments object or its properties are missing
        console.error("Invalid comments object:", comments);
      }
    } else {
      // console.error("Invalid comments format:", comments);
    }
  }

  function getArtistName(comment) {
    if (
      !isEmpty(comment.firstname) ||
      !isEmpty(comment.middlename) ||
      !isEmpty(comment.lastname) ||
      !isEmpty(comment.suffix)
    ) {
      const middleInitial = !isEmpty(comment.middlename)
        ? comment.middlename[0] + "."
        : "";
      return (
        comment.firstname +
        " " +
        middleInitial +
        " " +
        comment.lastname +
        (comment.suffix ? ", " + comment.suffix : "")
      );
    } else {
      return comment.username;
    }
  }

  function formatTimeAgo(dateTimeString) {
    const createdDateTime = new Date(dateTimeString);
    const currentDateTime = new Date();

    const diffInSeconds = Math.floor(
      (currentDateTime - createdDateTime) / 1000
    );

    const diffInMinutes = Math.floor(diffInSeconds / 60);
    const diffInHours = Math.floor(diffInSeconds / 3600);
    const diffInDays = Math.floor(diffInSeconds / 86400);
    const diffInMonths = Math.floor(diffInSeconds / 2592000);
    const diffInYears = Math.floor(diffInSeconds / 31536000);

    if (diffInYears > 0) {
      return diffInYears === 1 ? "1 year ago" : `${diffInYears} years ago`;
    } else if (diffInMonths > 0) {
      return diffInMonths === 1 ? "1 month ago" : `${diffInMonths} months ago`;
    } else if (diffInDays >= 7) {
      const weeks = Math.floor(diffInDays / 7);
      return weeks === 1 ? "1 week ago" : `${weeks} weeks ago`;
    } else if (diffInDays > 0) {
      return diffInDays === 1 ? "1 day ago" : `${diffInDays} days ago`;
    } else if (diffInHours > 0) {
      return diffInHours === 1 ? "1 hour ago" : `${diffInHours} hours ago`;
    } else if (diffInMinutes > 0) {
      return diffInMinutes === 1
        ? "1 minute ago"
        : `${diffInMinutes} minutes ago`;
    } else {
      return "Less than a minute ago";
    }
  }

  function isEmpty(value) {
    return value === null || value === undefined || value.trim() === "";
  }

  function formatDate(dateTimeString) {
    const options = {
      weekday: "long",
      year: "numeric",
      month: "long",
      day: "numeric",
      hour: "numeric",
      minute: "numeric",
      hour12: true,
    };
    const formattedDate = new Date(dateTimeString).toLocaleDateString(
      "en-US",
      options
    );
    return formattedDate;
  }

  function getArtworkIdFromUrl() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get("id");
  }

  // Function to fetch comments
  function fetchComments() {
    const artworkId = getArtworkIdFromUrl();

    if (artworkId !== null && artworkId !== undefined) {
      const fetchUrl = `fetch_comments.php?artwork_id=${artworkId}`;

      fetch(fetchUrl, {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
        },
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
          }
          return response.json();
        })
        .then((data) => {
          if (data.error) {
            console.error("Server error:", data.error);
          } else {
            updateComments(data);
          }
        })
        .catch((error) => {
          console.error("An error occurred:", error);
        });
    } else {
      console.error("Artwork ID is not available.");
    }
  }
  function updateCommentsCount() {
    const artworkId = getArtworkIdFromUrl();

    if (artworkId !== null && artworkId !== undefined) {
      const fetchUrl = `fetch_comments_count.php?artwork_id=${artworkId}`;

      fetch(fetchUrl, {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
        },
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
          }
          return response.json();
        })
        .then((data) => {
          const commentCount = data.commentCount;

          // Update the comments heading
          headingElement.textContent =
            commentCount === 1
              ? "COMMENT"
              : commentCount === 0
              ? "NO COMMENTS YET"
              : "COMMENTS";

          console.log("Comment Count:", commentCount);

          // Update the comments container
          updateComments(data.comments);

          const commentsCountElement = document.getElementById("comments-count");

          if (commentsCountElement) {
            commentsCountElement.textContent = "Comments: " + commentCount;
          }
        })
        .catch((error) => {
          console.error("An error occurred:", error);
        });
    } else {
      console.error("Artwork ID is not available.");
    }
  }

  // Fetch comments initially and then every .10 second
  fetchComments();
  updateCommentsCount();
  setInterval(() => {
    fetchComments();
    updateCommentsCount();
  }, 1000);
});
