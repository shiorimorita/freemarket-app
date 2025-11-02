const fileInput = document.querySelector(".common__img-input");
const fileButton = document.querySelector(".common__img-button");
const imagePreview = document.querySelector(".common__img-image");

fileButton.addEventListener("click", () => {
    fileInput.click();
});

fileInput.addEventListener("change", (e) => {
    const file = e.target.files[0];

    imagePreview.src = URL.createObjectURL(file);
    imagePreview.classList.add("has-image");
});
