const { createApp } = Vue;

createApp({
    data() {
        return {
            nama: '',
            whatsapp: '',
            tanggal: '',
            jumlah: 1,
            destinasi: '',
            catatan: '',
            prices: {
                weekday: 15000,
                weekend: 25000
            },
            wisataLookup: {},
            minDate: '',
            
            // Validation States
            errors: {
                nama: '',
                whatsapp: ''
            },
            
            // Calendar States
            currentMonth: new Date(),
            availability: {},
            holidays: [],
            maxQuota: 0,

            isLoadingAvailability: false
        };
    },
    watch: {
        destinasi(newVal) {
            if (newVal) {
                this.fetchAvailability();
            }
        },
        nama(val) {
            if (val && /[0-9]/.test(val)) {
                this.errors.nama = 'Nama lengkap tidak boleh mengandung angka.';
            } else {
                this.errors.nama = '';
            }
        },
        whatsapp(val) {
            if (val) {
                // Remove spaces and dashes for check
                const cleanVal = val.replace(/[\s\-]/g, '');
                if (/[^0-9]/.test(cleanVal)) {
                    this.errors.whatsapp = 'Nomor WhatsApp hanya boleh berisi angka.';
                } else if (cleanVal.length > 0 && cleanVal.length < 10) {
                    this.errors.whatsapp = 'Nomor WhatsApp minimal 10 digit.';
                } else if (cleanVal.length > 15) {
                    this.errors.whatsapp = 'Nomor WhatsApp maksimal 15 digit.';
                } else {
                    this.errors.whatsapp = '';
                }
            } else {
                this.errors.whatsapp = '';
            }
        }
    },
    mounted() {
        // Set min date to today
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        this.minDate = `${year}-${month}-${day}`;

        const jsonData = document.getElementById('wisata-data');
        if (jsonData) {
            try {
                this.wisataLookup = JSON.parse(jsonData.textContent);
                // Set default destination if only one
                const keys = Object.keys(this.wisataLookup);
                if (keys.length === 1) {
                    this.destinasi = keys[0];
                    this.updatePrice();
                }
            } catch (e) {
                console.error("Error parsing wisata data", e);
            }
        }
    },
    computed: {
        isWeekend() {
            if (!this.tanggal) return false;
            const date = new Date(this.tanggal);
            const day = date.getDay();
            return day === 0 || day === 6; 
        },
        unitPrice() {
            return this.isWeekend ? this.prices.weekend : this.prices.weekday;
        },
        totalHargaRaw() {
            return this.unitPrice * this.jumlah;
        },
        totalHarga() {
            return this.totalHargaRaw.toLocaleString('id-ID');
        },
        selectedDateHoliday() {
            if (!this.tanggal) return false;
            return this.holidays.includes(this.tanggal);
        },
        // Calendar Computed
        calendarTitle() {
            return this.currentMonth.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
        },
        calendarDays() {
            const year = this.currentMonth.getFullYear();
            const month = this.currentMonth.getMonth();
            
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            
            const days = [];
            
            // Padding for first week
            const startPadding = firstDay.getDay(); // 0 is Sunday
            for (let i = 0; i < startPadding; i++) {
                days.push({ day: null });
            }
            
            // Actual days
            for (let d = 1; d <= lastDay.getDate(); d++) {
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                const isPast = dateStr < this.minDate;
                const booked = this.availability[dateStr] || 0;
                const isHoliday = this.holidays.includes(dateStr);
                
                let status = 'available';
                if (isHoliday) {
                    status = 'closed';
                } else if (isPast) {
                    status = 'past';
                } else if (this.maxQuota > 0) {
                    const ratio = booked / this.maxQuota;
                    if (ratio >= 1) status = 'full';
                    else if (ratio >= 0.8) status = 'almost-full';
                }

                days.push({
                    day: d,
                    date: dateStr,
                    isPast,
                    isHoliday,
                    booked,
                    status
                });
            }
            
            return days;
        }
    },
    methods: {
        updatePrice() {
            if (this.wisataLookup[this.destinasi]) {
                const p = this.wisataLookup[this.destinasi];
                this.prices.weekday = p.weekday;
                this.prices.weekend = p.weekend;
                this.fetchAvailability();
            }
        },
        
        async fetchAvailability() {
            if (!this.destinasi) return;
            
            this.isLoadingAvailability = true;
            const monthStr = `${this.currentMonth.getFullYear()}-${String(this.currentMonth.getMonth() + 1).padStart(2, '0')}`;
            const t = new Date().getTime(); // Cache buster
            
            try {
                const response = await fetch(`controllers/process/get_availability.php?destinasi=${encodeURIComponent(this.destinasi)}&month=${monthStr}&v=${t}`);
                const res = await response.json();
                
                if (res.status === 'success') {
                    this.maxQuota = res.data.max_quota;
                    this.availability = res.data.bookings;
                    this.holidays = res.data.holidays || [];
                    console.log("Holidays for " + this.destinasi + ":", this.holidays);
                }


            } catch (e) {
                console.error("Error fetching availability", e);
            } finally {
                this.isLoadingAvailability = false;
            }
        },
        
        changeMonth(offset) {
            const newDate = new Date(this.currentMonth);
            newDate.setMonth(newDate.getMonth() + offset);
            
            // Don't go to past months if today is in currentMonth
            const today = new Date();
            const minMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            if (newDate < minMonth) return;

            this.currentMonth = newDate;
            this.fetchAvailability();
        },
        
        selectDate(day) {
            if (!day.day || day.isPast || day.status === 'full' || day.status === 'closed') return;
            this.tanggal = day.date;
        },

        
        submitBooking() {
            if (this.tanggal < this.minDate) {
                alert('Mohon pilih tanggal yang valid (hari ini atau mendatang).');
                return;
            }

            if (this.selectedDateHoliday) {
                alert('Maaf, destinasi ini sedang tutup pada tanggal yang Anda pilih. Mohon pilih tanggal lain.');
                return;
            }

            if (this.nama && this.whatsapp && this.tanggal && this.destinasi) {
                this.$refs.bookingForm.submit();
            } else {
                alert('Mohon lengkapi formulir pemesanan dengan benar.');
            }
        }
    }
}).mount('#booking-app');
