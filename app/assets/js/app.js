(() => {
    const POLL_INTERVAL = 10000;
    const WAKE_POLL_INTERVAL = 5000;

    const statusBadge = document.getElementById('statusBadge');
    const statusDot   = document.getElementById('statusDot');
    const statusText  = document.getElementById('statusText');
    const wakeBtn     = document.getElementById('wakeBtn');
    const rdpBtn      = document.getElementById('rdpBtn');
    const lastAction  = document.getElementById('lastAction');
    const toastCont   = document.getElementById('toastContainer');

    let isOnline    = null;
    let isWaking    = false;
    let pollTimer   = null;

    async function apiPost(action) {
        const res = await fetch('/api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action, csrf_token: window.CSRF_TOKEN }),
        });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        return res.json();
    }

    function setStatus(online) {
        isOnline = online;
        statusBadge.className = 'status-badge ' + (isWaking ? 'waking' : (online ? 'online' : 'offline'));
        statusText.textContent = isWaking ? 'Bezig met opstarten…' : (online ? 'Online' : 'Offline');

        wakeBtn.disabled = online;

        if (online) {
            rdpBtn.style.display = '';
            lastAction.textContent = '';
        } else {
            rdpBtn.style.display = 'none';
        }
    }

    async function pollStatus() {
        try {
            const data = await apiPost('status');
            const online = !!data.online;

            if (isWaking && online) {
                isWaking = false;
                showToast('Computer is online!', 'success');
            }

            setStatus(online);
        } catch {
            // Silently ignore network errors during polling
        }

        const interval = isWaking ? WAKE_POLL_INTERVAL : POLL_INTERVAL;
        pollTimer = setTimeout(pollStatus, interval);
    }

    wakeBtn.addEventListener('click', async () => {
        wakeBtn.disabled = true;
        wakeBtn.textContent = 'Verzenden…';

        try {
            const data = await apiPost('wake');
            if (data.success) {
                isWaking = true;
                setStatus(false);
                showToast('Magic packet verzonden!', 'success');
                lastAction.textContent = 'Magic packet verzonden om ' + new Date().toLocaleTimeString('nl-NL');

                clearTimeout(pollTimer);
                pollTimer = setTimeout(pollStatus, WAKE_POLL_INTERVAL);
            } else {
                showToast('Versturen mislukt.', 'error');
                wakeBtn.disabled = false;
            }
        } catch {
            showToast('Fout bij verzenden.', 'error');
            wakeBtn.disabled = isOnline;
        }

        wakeBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24">
            <path fill="currentColor" d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm1 14.93V16a1 1 0 0 0-2 0v.93A8 8 0 0 1 4.07 12H5a1 1 0 0 0 0-2h-.93A8 8 0 0 1 11 4.07V5a1 1 0 0 0 2 0v-.93A8 8 0 0 1 19.93 11H19a1 1 0 0 0 0 2h.93A8 8 0 0 1 13 16.93Z"/>
        </svg> Wake Computer`;
    });

    function showToast(message, type = '') {
        const t = document.createElement('div');
        t.className = 'toast ' + type;
        t.textContent = message;
        toastCont.appendChild(t);
        setTimeout(() => t.remove(), 4000);
    }

    pollStatus();
})();
