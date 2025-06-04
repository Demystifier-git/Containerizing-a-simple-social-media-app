const commentButtons = document.querySelectorAll(".commentButton");
commentButtons.forEach((commentButton) => {
  commentButton.addEventListener("click", async () => {
    const isUserLoggedInURL = "endpoints/isUserLoggedIn.php";
    var res = await fetch(isUserLoggedInURL);
    var data = await res.json();
    if (!data.isUserLoggedIn) {
      alert("Log in / Sign up to engage posts");
      return;
    }
    const postID = commentButton.dataset.postId;
    const formData = new FormData();
    formData.append("postID", postID);
    const commentInput = document.querySelector("#commentInput" + postID);
    // console.log(commentInput)
    commentInput.focus();
  });
});
const commentForms = document.querySelectorAll(".comment-form form");
commentForms.forEach((commentForm) => {
  commentForm.addEventListener("submit", (e) => {
    e.preventDefault();
    const postID = commentForm.dataset.postId;
    const commentInput = document.querySelector("#commentInput" + postID);

    if (!commentInput.value) {
      alert("Comment cannot be empty");
      return;
    }
    e.target.submit();
  });
});
