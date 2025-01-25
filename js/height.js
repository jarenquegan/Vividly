document.addEventListener("DOMContentLoaded", function() {
    // Find the artwork image element
    var artworkImage = document.querySelector('.play-container img');

    // Listen for the 'load' event on the image
    artworkImage.addEventListener('load', function() {
        // Get the height of the loaded artwork image
        var artworkHeight = artworkImage.clientHeight;

        // Set the min-height property of the .play-container
        var playContainer = document.querySelector('.play-container');
        playContainer.style.minHeight = artworkHeight + 'px';
    });
});
