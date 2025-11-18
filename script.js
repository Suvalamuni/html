document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector(".contact-form");
  if (form) {
    form.addEventListener("submit", e => {
      e.preventDefault();
      alert("Message sent successfully! We'll get back to you soon.");
      form.reset();
    });
  }
});
