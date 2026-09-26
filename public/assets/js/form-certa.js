document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('buktiFotoInput');
    const box = input ? input.previousElementSibling : null;

    if (input && box) {
        const defaultHtml = box.innerHTML;
        input.addEventListener('change', function () {
            if (input.files.length > 0) {
                const names = Array.from(input.files).map((f) => f.name).join(', ');
                box.innerHTML = '<i class="bi bi-check-circle-fill fs-2 d-block mb-1 text-success"></i>' + names;
            } else {
                box.innerHTML = defaultHtml;
            }
        });
    }
});
