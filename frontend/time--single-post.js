const postHeadTime = document.querySelector(".post-head small");
const raw = postHeadTime.innerText.trim(); // "2025-05-11 12:52:23"
const [datePart, timePart] = raw.split(" ");
const [year, month, day] = datePart.split("-").map(Number);
const [hour, minute, second] = timePart.split(":").map(Number);

const utcTime = new Date(Date.UTC(year, month - 1, day, hour, minute, second));
postHeadTime.innerText =
  utcTime.toDateString() + ", " + utcTime.toLocaleTimeString();

const commentTimes = document.querySelectorAll(".comment small");
commentTimes.forEach((commentTime) => {
const raw = commentTime.innerText.trim(); // "2025-05-11 12:52:23"
const [datePart, timePart] = raw.split(" ");
const [year, month, day] = datePart.split("-").map(Number);
const [hour, minute, second] = timePart.split(":").map(Number);

const utcTime = new Date(Date.UTC(year, month - 1, day, hour, minute, second));
commentTime.innerText =
  utcTime.toDateString() + ", " + utcTime.toLocaleTimeString();
});
