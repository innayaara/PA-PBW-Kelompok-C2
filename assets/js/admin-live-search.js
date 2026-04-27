/**
 * Vue.js Admin Live Search
 */
const { createApp } = Vue;

// --- 1. Booking Admin App ---
if (document.getElementById('booking-admin-app')) {
    createApp({
        data() {
            return {
                bookings: [],
                search: '',
                status: ''
            };
        },
        mounted() {
            const jsonData = document.getElementById('booking-data');
            if (jsonData) {
                try {
                    this.bookings = JSON.parse(jsonData.textContent);
                } catch (e) {
                    console.error("Error parsing booking data", e);
                }
            }
        },
        computed: {
            filteredBookings() {
                return this.bookings.filter(item => {
                    const matchSearch = (
                        item.kode_booking.toLowerCase().includes(this.search.toLowerCase()) ||
                        item.nama_lengkap.toLowerCase().includes(this.search.toLowerCase()) ||
                        item.whatsapp.toLowerCase().includes(this.search.toLowerCase()) ||
                        item.destinasi.toLowerCase().includes(this.search.toLowerCase())
                    );
                    
                    const matchStatus = this.status === '' || item.status === this.status;
                    
                    return matchSearch && matchStatus;
                });
            }
        },
        methods: {
            formatDate(dateStr) {
                if (!dateStr) return '-';
                const date = new Date(dateStr);
                const options = { day: '2-digit', month: 'short', year: 'numeric' };
                return date.toLocaleDateString('id-ID', options);
            },
            formatNumber(num) {
                return parseInt(num).toLocaleString('id-ID');
            },
            resetFilter() {
                this.search = '';
                this.status = '';
            }
        }
    }).mount('#booking-admin-app');
}

// --- 2. Wisata Admin App ---
if (document.getElementById('wisata-admin-app')) {
    createApp({
        data() {
            return {
                wisata: [],
                search: '',
                status: ''
            };
        },
        mounted() {
            const jsonData = document.getElementById('wisata-data');
            if (jsonData) {
                try {
                    this.wisata = JSON.parse(jsonData.textContent);
                } catch (e) {
                    console.error("Error parsing wisata data", e);
                }
            }
        },
        computed: {
            filteredWisata() {
                return this.wisata.filter(item => {
                    const searchLower = this.search.toLowerCase();
                    const matchSearch = (
                        item.nama_wisata.toLowerCase().includes(searchLower) ||
                        (item.kategori && item.kategori.toLowerCase().includes(searchLower)) ||
                        (item.lokasi && item.lokasi.toLowerCase().includes(searchLower))
                    );
                    
                    const matchStatus = this.status === '' || item.status === this.status;
                    
                    return matchSearch && matchStatus;
                });
            }
        },
        methods: {
            formatNumber(num) {
                return parseInt(num).toLocaleString('id-ID');
            },
            truncate(text, length) {
                if (!text) return '';
                return text.length > length ? text.substring(0, length) + '...' : text;
            },
            resetFilter() {
                this.search = '';
                this.status = '';
            }
        }
    }).mount('#wisata-admin-app');
}

// --- 3. Galeri Admin App ---
if (document.getElementById('galeri-admin-app')) {
    createApp({
        data() {
            return {
                galeri: [],
                search: '',
                status: ''
            };
        },
        mounted() {
            const jsonData = document.getElementById('galeri-data');
            if (jsonData) {
                try {
                    this.galeri = JSON.parse(jsonData.textContent);
                } catch (e) {
                    console.error("Error parsing galeri data", e);
                }
            }
        },
        computed: {
            filteredGaleri() {
                return this.galeri.filter(item => {
                    const searchLower = this.search.toLowerCase();
                    const matchSearch = (
                        item.judul_foto.toLowerCase().includes(searchLower) ||
                        (item.nama_wisata && item.nama_wisata.toLowerCase().includes(searchLower)) ||
                        (item.kategori && item.kategori.toLowerCase().includes(searchLower))
                    );
                    
                    const matchStatus = this.status === '' || item.status === this.status;
                    
                    return matchSearch && matchStatus;
                });
            }
        },
        methods: {
            truncate(text, length) {
                if (!text) return '';
                return text.length > length ? text.substring(0, length) + '...' : text;
            },
            resetFilter() {
                this.search = '';
                this.status = '';
            }
        }
    }).mount('#galeri-admin-app');
}
