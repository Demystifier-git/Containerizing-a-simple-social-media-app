const delete_post_forms = document.querySelectorAll(".delete-post-form form");
delete_post_forms.forEach((delete_post_form) => {
  delete_post_form.addEventListener("submit", (e) => {
    e.preventDefault();
    const choice = confirm("Are you sure you want to delete this post?");
    if (choice) {
      e.target.submit();
    }
  });
});
