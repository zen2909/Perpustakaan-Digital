import { Html5Qrcode } from "html5-qrcode";

document.addEventListener("DOMContentLoaded", () => {
    const scanButtons = document.querySelectorAll(".scan-btn");
    let activeScanner = null;

    scanButtons.forEach((btn) => {
        const loanId = btn.dataset.loan;
        const modalId = btn.dataset.modal;

        const modal = document.getElementById(modalId);
        const containerId = `scan-container-return${loanId}`;
        const closeBtn = modal.querySelector(".close-scan-modal");

        let html5QrCode;
        let isScanning = false;

        // === BUKA MODAL & START SCAN ===
        btn.addEventListener("click", async () => {
            modal.showModal();

            await new Promise((resolve) => setTimeout(resolve, 300));

            if (isScanning) return;
            isScanning = true;

            html5QrCode = new Html5Qrcode(containerId);
            activeScanner = html5QrCode;

            try {
                await html5QrCode.start(
                    { facingMode: "environment" },
                    { fps: 10, qrbox: 250 },
                    async (decodedText) => {
                        console.log("QR:", decodedText);

                        isScanning = false;
                        await html5QrCode.stop();
                        html5QrCode.clear();

                        // 👉 kirim ke backend
                        fetch("/admin/loans/scan-return", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document
                                    .querySelector('meta[name="csrf-token"]')
                                    .getAttribute("content"),
                            },
                            body: JSON.stringify({
                                token_return: decodedText,
                            }),
                        })
                            .then((res) => res.json())
                            .then((data) => {
                                if (data.success) {
                                    modal.close();
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 500);
                                } else {
                                    alert("QR tidak valid");
                                }
                            });
                    },
                );
            } catch (err) {
                console.error("Gagal membuka kamera:", err);
                isScanning = false;
            }
        });

        // === TUTUP MODAL ===
        closeBtn.addEventListener("click", async () => {
            modal.close();

            if (html5QrCode && isScanning) {
                await html5QrCode.stop();
                html5QrCode.clear();
                isScanning = false;
            }
        });

        // === JIKA MODAL DITUTUP MANUAL ===
        modal.addEventListener("close", async () => {
            if (html5QrCode && isScanning) {
                await html5QrCode.stop();
                html5QrCode.clear();
                isScanning = false;
            }
        });
    });
});
