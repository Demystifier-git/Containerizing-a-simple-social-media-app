const likeButtons = document.querySelectorAll(".likeButton");
likeButtons.forEach((likeButton) => {
  likeButton.addEventListener("click", async () => {
    const isUserLoggedInURL = "endpoints/isUserLoggedIn.php";
    var res = await fetch(isUserLoggedInURL);
    var data = await res.json();
    if (!data.isUserLoggedIn) {
      alert("Log in / Sign up to engage posts");
      return;
    }
    const postID = likeButton.dataset.postId;
    var formData = new FormData();
    formData.append("postID", postID);
    const doesPostExist = "endpoints/doesPostExist.php";
    var res = await fetch(doesPostExist, {
      method: "post",
      body: formData,
    });
    var data = await res.json();
    if (!data.doesPostExist) {
      alert("Cannot like, post has been deleted");
      window.location.href = "posts.php";
      return;
    }
    const likeURL = "endpoints/like.php";
    var res = await fetch(likeURL, {
      method: "POST",
      body: formData,
    });
    var data = await res.json();
    if (data.haveLiked) {
      const likeButtonIcon = document.querySelector("#likeButton" + postID);
      likeButtonIcon.innerText = "favorite";
      likeButtonIcon.style.color = "red";
      const likeCounter = document.querySelector("#likeCounter" + postID);
      likeCounter.innerText = data.totalLikes;
    } else {
      const likeButtonIcon = document.querySelector("#likeButton" + postID);
      likeButtonIcon.innerText = "favorite_outline";
      likeButtonIcon.style.color = "inherit";
      const likeCounter = document.querySelector("#likeCounter" + postID);
      likeCounter.innerText = data.totalLikes;
    }
  });
});
