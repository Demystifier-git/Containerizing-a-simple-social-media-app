const form = document.querySelector("form")
form.addEventListener("submit", (e) => {
  e.preventDefault()

  const username = document.querySelector("#username")
  const password = document.querySelector("#password")

  if(!username.value || !password.value) {
    alert("Fields cannot be empty")
    return
  }

  e.target.submit()
})