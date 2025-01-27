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

// Run the function on page load to handle pre-selected filters
window.onload = toggleFilterValue;

function showNotification(message, type) {
    const notification = document.getElementById("notification");
    notification.innerText = message;

    // Set the type of message (success, error, info, warning)
    notification.className = `notification ${type} show`;

    // Automatically hide the notification after 3 seconds
    setTimeout(() => {
        notification.className = "notification"; // Reset to default
    }, 3000);
}

function toggleSidebar(icon) {
    const sidebar = document.getElementById('sidebar');
    const header = document.querySelector('header');
    const container = document.querySelector('.container');

    // Toggle the collapsed state for sidebar, header, and container
    sidebar.classList.toggle('collapsed');
    header.classList.toggle('collapsed');
    container.classList.toggle('collapsed');

    // Toggle the menu icon animation to indicate open/close state
    icon.classList.toggle('open');
}

