// Show Video
let playButton = document.querySelector(".play-artwork");
let video = document.querySelector(".video-container");
let myvideo = document.querySelector("#myvideo");
let closebtn = document.querySelector(".close-video");

playButton.onclick = () => {
  video.classList.add("show-video");
  // Auto Play When Click On Button
  // myvideo.play();
};

closebtn.onclick = () => {
  video.classList.remove("show-video");
  // Pause On Close Video
  // myvideo.pause();
};


