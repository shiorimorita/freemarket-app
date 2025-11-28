const fileInput = document.querySelector(".common-img-input");
const fileButton = document.querySelector(".common-img-button");
const imagePreview = document.querySelector(".js-img-preview");

fileButton.addEventListener("click", () => {
    fileInput.click();
});

fileInput.addEventListener("change", (e) => {
    const file = e.target.files[0];

    imagePreview.src = URL.createObjectURL(file);
    imagePreview.classList.add("has-image");
});
