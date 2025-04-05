document.addEventListener('DOMContentLoaded', () => {
    console.log("UI Apotek aktif!");
  
    // Contoh: Toggle sidebar manual (jika ingin pakai tombol)
    const sidebar = document.getElementById('sidebar');
  
    // Jika nanti kamu ingin pakai tombol untuk toggle:
    // const toggleButton = document.getElementById('toggle-sidebar');
    // toggleButton.addEventListener('click', () => {
    //   sidebar.classList.toggle('expanded');
    // });
  
    // Placeholder untuk interaksi lain
    // Misal klik kategori tampil produk tertentu
    const kategoriCards = document.querySelectorAll('.kategori-card');
    kategoriCards.forEach((card) => {
      card.addEventListener('click', () => {
        alert(Kamu memilih kategori: ${card.innerText.trim()});
      });
    });
  });