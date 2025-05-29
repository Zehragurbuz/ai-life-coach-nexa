function toggleDetails(card) {
    const details = card.querySelector('.goal-details');
    if (!details) return;

    if (details.style.display === "block") {
        details.style.display = "none";
    } else {
        details.style.display = "block";
    }
}
