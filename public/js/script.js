// Wait for the DOM to be fully loaded before running the script
document.addEventListener("DOMContentLoaded", function () {
  const mobileMenuToggler = document.getElementById("mobileMenuToggler");
  const mobileMenuPanel = document.getElementById("mobileMenuPanel");
  const mobileMenuOverlay = document.getElementById("mobileMenuOverlay");
  const closeMobileMenu = document.getElementById("closeMobileMenu");
  const body = document.body;

  // Function to open the menu
  function openMenu() {
    if (mobileMenuPanel && mobileMenuOverlay) {
      mobileMenuPanel.classList.add("show");
      mobileMenuOverlay.classList.add("show");
      body.classList.add("body-no-scroll"); // Prevent body from scrolling
    }
  }

  // Function to close the menu
  function closeMenu() {
    if (mobileMenuPanel && mobileMenuOverlay) {
      mobileMenuPanel.classList.remove("show");
      mobileMenuOverlay.classList.remove("show");
      body.classList.remove("body-no-scroll"); // Allow body to scroll again
    }
  }

  // Event Listeners for mobile menu
  if (mobileMenuToggler) {
    mobileMenuToggler.addEventListener("click", openMenu);
  }
  if (closeMobileMenu) {
    closeMobileMenu.addEventListener("click", closeMenu);
  }
  if (mobileMenuOverlay) {
    // Close menu if user clicks on the overlay (outside the panel)
    mobileMenuOverlay.addEventListener("click", closeMenu);
  }
  // --- Function to update Date and Time ---
  function updateDateTime() {
    const now = new Date();
    const dateTimeContainer = document.getElementById("date-time-container");

    if (dateTimeContainer) {
      // Options for Gregorian date
      const gregorianOptions = {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
      };
      // Options for Hijri date (Islamic calendar)
      const hijriOptions = {
        calendar: "islamic-civil",
        year: "numeric",
        month: "long",
        day: "numeric",
      };
      // Options for time
      const timeOptions = {
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
        hour12: true,
      };

      // Format dates and time using Intl.DateTimeFormat for best performance and localization
      const gregorianDate = new Intl.DateTimeFormat(
        "ar-EG",
        gregorianOptions
      ).format(now);
      const hijriDate = new Intl.DateTimeFormat(
        "ar-SA-u-ca-islamic-civil",
        hijriOptions
      ).format(now);
      const timeString = new Intl.DateTimeFormat("ar-EG", timeOptions).format(
        now
      );

      // Construct the final string as seen in the image
      // We use <span> to be able to style parts differently if needed
      dateTimeContainer.innerHTML = `
                <span><b class="highlight">${gregorianDate}</b> | ${hijriDate}</span>
                <span class="mx-2">|</span>
                <span>${timeString}</span>
            `;
    }
  }

  // --- Function to set the Copyright Year ---
  function updateCopyrightYear() {
    const yearSpan = document.getElementById("copyright-year");
    if (yearSpan) {
      yearSpan.textContent = new Date().getFullYear();
    }
  }

  // --- Initial calls and intervals ---

  // Call it once immediately to display time without delay
  updateDateTime();
  // Set an interval to update the time every second
  setInterval(updateDateTime, 1000);

  // Set the copyright year
  updateCopyrightYear();
});
