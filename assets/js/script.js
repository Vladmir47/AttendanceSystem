// Form validation
function validateForm(form) {
    const inputs = form.querySelectorAll("input, select");
    let isValid = true;

    inputs.forEach((input) => {
        if (!input.value.trim()) {
            input.style.border = "1px solid red";
            isValid = false;
        } else {
            input.style.border = "1px solid #ccc";
        }
    });

    if (!isValid) {
        alert("Please fill out all required fields.");
    }

    return isValid;
}

// Confirm deletion of a student
function confirmDelete() {
    return confirm("Are you sure you want to delete this information?");
}

function confirmDelete() {
    return confirm("Are you sure you want to delete this information?");
}
