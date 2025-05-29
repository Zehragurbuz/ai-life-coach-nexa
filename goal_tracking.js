function toggleDetails(card) {
    const detail = card.querySelector('.goal-details');
    detail.style.display = (detail.style.display === 'block') ? 'none' : 'block';
}

function startTimer(e, minutes) {
    e.stopPropagation(); // Kartın açılma/kapanma olayını engelle
    const timerEl = e.target.nextElementSibling;
    let time = minutes * 60;

    clearInterval(timerEl.intervalId);

    function updateTimer() {
        const mins = String(Math.floor(time / 60)).padStart(2, '0');
        const secs = String(time % 60).padStart(2, '0');
        timerEl.textContent = `${mins}:${secs}`;
        if (time > 0) time--;
        else clearInterval(timerEl.intervalId);
    }

    updateTimer();
    timerEl.intervalId = setInterval(updateTimer, 1000);
}
