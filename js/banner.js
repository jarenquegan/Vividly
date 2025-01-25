$(document).ready(function () {
	var currentIndex = 0;
	var topArtworks = [];

	function getTopArtworks() {
		// Send an AJAX request to retrieve the top 10 most liked artworks from the server
		$.ajax({
			url: "top_artworks.php",
			type: "GET",
			dataType: "json",
			success: function (data) {
				topArtworks = data; // Store all 10 artworks

				// Update the content based on the current index
				updateContent();

				// Increment the index for the next iteration
				currentIndex = (currentIndex + 1) % topArtworks.length;
			},
			error: function (xhr, status, error) {
				console.log(error);
			},
			complete: function () {
				// Call the getTopArtworks function again after 10 seconds
				setTimeout(getTopArtworks, 10000);
			},
		});
	}

	function updateContent() {
		var currentArtwork = topArtworks[currentIndex];

		// Update the specific variables in the home section
		$(".home-img")
			.attr("src", "artworks/" + currentArtwork.image_url)
			.attr("alt", currentArtwork.title);
		$(".home-title").text(currentArtwork.title);

		if (currentArtwork.username) {
			$(".artist-name").text("@" + currentArtwork.username);
		} else {
			$(".artist-name").text("No artist available");
		}

		$("#open-btn").attr(
			"href",
			"open-now.php?id=" + currentArtwork.artwork_id
		);
	}

	// Call the getTopArtworks function initially
	getTopArtworks();
});
