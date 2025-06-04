const postButton__in_title = document.querySelector(".post-button button");
if (postButton__in_title) {
  postButton__in_title.addEventListener("click", () => {
    const modalBackground = document.querySelector(".modal-background");
    modalBackground.classList.toggle("hidden");
  });
}

const closeModal = document.querySelector(".modal-head i");
closeModal.addEventListener("click", () => {
  const modalBackground = document.querySelector(".modal-background");
  modalBackground.classList.toggle("hidden");
});

const postForm = document.querySelector(".post-form form");
postForm.addEventListener("submit", (e) => {
  e.preventDefault();

  const postContent = document.querySelector(".post-form textarea");
  if (!postContent.value) {
    alert("Cannot make an empty post");
    return;
  }

  e.target.submit();
});
