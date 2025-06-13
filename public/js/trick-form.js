document.addEventListener('DOMContentLoaded', () => {
    const collectionHolder = document.getElementById('images-collection');
    const addButton = document.getElementById('add-image-btn');
    let index = collectionHolder.querySelectorAll('.image-form').length;

    addButton.addEventListener('click', function () {
        const prototype = collectionHolder.dataset.prototype;
        const newFormHtml = prototype.replace(/__name__/g, index);
        const newForm = document.createElement('div');
        newForm.classList.add('image-form');
        newForm.innerHTML = newFormHtml;
        collectionHolder.appendChild(newForm);
        index++;
    });
});
