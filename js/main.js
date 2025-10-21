// Sidebar Toggle
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    sidebar.classList.toggle("hidden");
  }
  
  // Form Validation Example
  function validateForm(formId) {
    const form = document.getElementById(formId);
    form.addEventListener("submit", function (e) {
      const inputs = form.querySelectorAll("input[required], select[required]");
      let valid = true;
      inputs.forEach((input) => {
        if (!input.value.trim()) {
          input.classList.add("border-red-500");
          valid = false;
        } else {
          input.classList.remove("border-red-500");
        }
      });
      if (!valid) {
        e.preventDefault();
        alert("Please fill in all required fields.");
      }
    });
  }
  