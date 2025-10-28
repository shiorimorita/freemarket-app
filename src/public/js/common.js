const fileInput = document.querySelector(".profile__img-input");
const fileButton = document.querySelector(".profile__img-button");
const imagePreview = document.querySelector(".profile__img-image");

fileButton.addEventListener("click", () => {
    fileInput.click();
});

fileInput.addEventListener("change", (e) => {
    const file = e.target.files[0];

    imagePreview.src = URL.createObjectURL(file);
    imagePreview.classList.add("has-image");
});
