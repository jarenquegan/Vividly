// Set the initial preview image
var previewImage = document.getElementById("previewImage");
var lastDisplayedPhoto = previewImage.src; // Store the last displayed photo

// Update the image source when a new file is selected
var editPicInput = document.getElementById("artwork");
editPicInput.addEventListener("change", function (event) {
  var file = event.target.files[0];
  var reader = new FileReader();
  reader.onload = function (e) {
    previewImage.src = e.target.result;
    lastDisplayedPhoto = previewImage.src; // Update the last displayed photo
  };
  reader.readAsDataURL(file);
});
// Handle the case when the user removes the current selection
editPicInput.addEventListener("input", function () {
  if (editPicInput.files.length === 0) {
    previewImage.src = lastDisplayedPhoto; // Reset to the last displayed photo
  }
});

function removePhoto() {
  var previewImage = document.getElementById("previewImage");
  var editPicInput = document.getElementById("artwork");
  var defaultImage = "default_photo.png";
  var defaultImageInput = document.getElementById("defaultImage");

  lastDisplayedPhoto = previewImage.src; // Update the last displayed photo
  previewImage.src = "artworks/" + defaultImage;
  editPicInput.value = ""; // Clear the file input
  defaultImageInput.value = defaultImage; // Update the hidden input field value
}
