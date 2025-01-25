document.addEventListener("DOMContentLoaded", function () {
  const notificationsContainer = document.getElementById("notifications-container");
  const notificationsLink = document.getElementById("notificationsLink");

  function updateNotifications(notifications) {
    console.log("Updating Notifications");
    const notificationList = notifications.map((notification) => {
      let hrefValue;
      
      if (notification.notiftype === "follow") {
        hrefValue = `artist.php?artist=${notification.sender_id}`;
      } else {
        hrefValue = `open-now.php?id=${notification.artworkId}`;
      }

      let hrefValueDelete = `delete.php?notif_id=${notification.notification_id}&confirm=true&notif=true`;

      const readIcon = notification.is_read
        ? "<p><i class='bx bx-check' ></i> Read Notification</p>"
        : "<p  style='color: #1D9BF0;'><i class='bx bx-x' ></i> Unread Notification</p>";

      return `
        <li data-notification-id="${notification.notification_id}">
          <a href="${hrefValue}" class="artist-link">
            <div class="artist-item" style="border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 15px;">
              <div class="artist_pic">
                <img src="images/${notification.sender_artist_pic}" loading="lazy" alt="${notification.sender_username}">
              </div>
              <div class="artist-details">
                <span style="display: flex; align-items: center;">
                  <span class="spanspan">
                    <h3>
                      ${
                        notification.sender_firstname || notification.sender_lastname
                          ? `${notification.sender_firstname || ""} ${
                              notification.sender_middlename
                                ? notification.sender_middlename[0] + "."
                                : ""
                            } ${notification.sender_lastname || ""}`
                          : notification.sender_username
                      }
                      ${notification.sender_suffix ? `, ${notification.sender_suffix}` : ""}
                    </h3>
                  </span>
                  <span>
                    <p>
                      ${(() => {
                        const createdDateTime = new Date(notification.created_at);
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
                      })()}
                      <i class='bx bx-time'></i>
                    </p>
                  </span>
                </span>
                ${readIcon}
                <p>${notification.notification_data}</p>
                <p><i class='bx bx-calendar-alt'></i> ${formatDate(notification.created_at)}</p>
                <a href="${hrefValueDelete}" style="text-decoration: none; color: inherit;">
                  <p style="text-align: right;" class="dashspan">
                    Delete <i class='bx bx-trash'></i>
                  </p>
                </a>                
              </div>
            </div>
          </a>
        </li>
      `;
    });

    notificationsContainer.innerHTML = `
      <span style="display: flex; align-items: center;">
        <span class="spanspan">
          <h3 style="margin-bottom: 10px; display: flex; align-items: center;">
            ${notifications.length === 1 ? "NOTIFICATION" : "NOTIFICATIONS"}
          </h3>
        </span>
        <span>
          ${notifications.length >= 1 ? `
            <a href="delete.php?confirm=true&clear=true&artist_id=${currentArtistId}" style="text-decoration: none; color: inherit;">
              <p style="text-align: right;" class="dashspan">
                Clear Notifications <i class='bx bx-trash'></i>
              </p>
            </a>` : ''}
        </span>
      </span>
      <ul class="artwork-list">${notificationList.join("")}</ul>
      <div style="border-top: 0px solid #161616; padding-bottom: 10px;"></div>
    `;

    const hasUnreadNotifications = notifications.some((notification) => !notification.is_read);
    console.log("Has Unread Notifications:", hasUnreadNotifications); // Log whether there are unread notifications

    // Add or remove class based on unread notifications
    notificationsLink.classList.toggle("has-unread-notifications", hasUnreadNotifications);

    // Store the information in session storage
    sessionStorage.setItem("hasUnreadNotifications", hasUnreadNotifications);
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

  function fetchNotifications() {
    fetch("fetch_notifications.php", {
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
        updateNotifications(data);
      })
      .catch((error) => {
        console.error("An error occurred:", error);
      });
  }

  function markNotificationAsRead(notificationId) {
    fetch("mark_as_read.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `action=mark_as_read&notification_id=${notificationId}`,
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
      })
      .then((data) => {
        console.log("Notification marked as read:", data);
      })
      .catch((error) => {
        console.error("An error occurred while marking notification as read:", error);
      });
  }

  notificationsContainer.addEventListener("click", function (event) {
    const target = event.target;
    const closestNotificationItem = target.closest("li");

    if (closestNotificationItem && closestNotificationItem.hasAttribute("data-notification-id")) {
      const notificationId = closestNotificationItem.getAttribute("data-notification-id");

      if (!notificationId) {
        console.error("Error: Notification ID is undefined.");
      }

      markNotificationAsRead(notificationId);
    }
  });

  fetchNotifications();
  setInterval(fetchNotifications, 100);

  // Retrieve information from session storage on page load
  const storedHasUnreadNotifications = sessionStorage.getItem("hasUnreadNotifications");
  if (storedHasUnreadNotifications) {
    notificationsLink.classList.add("has-unread-notifications");
  }
});