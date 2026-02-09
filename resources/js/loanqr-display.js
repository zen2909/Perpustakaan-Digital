document.addEventListener("DOMContentLoaded", () => {
    const qrButtons = document.querySelectorAll(".btn-qr");
    const closeBtn = document.querySelectorAll(".close-qr-modal");

    qrButtons.forEach((btn) => {
        btn.addEventListener("click", () => {
            const modalId = btn.dataset.modal;
            const modal = document.getElementById(modalId);

            if (!modal) {
                console.error("Modal tidak ditemukan:", modalId);
                return;
            }

            modal.showModal();
        });
    });

    closeBtn.forEach((btn) => {
        btn.addEventListener("click", () => {
            const modalId = btn.dataset.modal;
            const modal = document.getElementById(modalId);

            if (!modal) {
                console.error("Modal tidak ditemukan:", modalId);
                return;
            }

            modal.close();
        });
    });
});
