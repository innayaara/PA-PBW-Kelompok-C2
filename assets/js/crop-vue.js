const { createApp } = Vue;

createApp({
  data() {
    return {
      // Cropping States
      groups: {},
      activeGroupId: null,
      cropper: null,
      modal: null,
      imageToCropSrc: null,

      // Form Validation States
      formData: {},
      errors: {}
    };
  },
  mounted() {
    this.initGroups();
    this.initModal();
    this.initFormData();
  },
  computed: {
    isFormValid() {
      // Check if there are any error messages
      const hasErrors = Object.values(this.errors).some(err => err !== null && err !== '');
      
      // Check if all required crop groups have data
      const hasMissingCrops = Object.values(this.groups).some(g => g.required && !g.croppedData);
      
      return !hasErrors && !hasMissingCrops;
    }
  },
  methods: {
    // --- Cropping Methods ---
    initGroups() {
      const elements = document.querySelectorAll('.js-crop-group');
      elements.forEach((el, index) => {
        const id = el.getAttribute('data-id') || `group-${index}`;
        this.groups[id] = {
          hasFile: false,
          previewUrl: null,
          croppedData: null,
          aspectRatio: this.parseAspectRatio(el.getAttribute('data-aspect-ratio')),
          label: el.getAttribute('data-label') || 'gambar',
          required: el.getAttribute('data-required') === 'true'
        };
      });
    },

    parseAspectRatio(ratioStr) {
      if (!ratioStr) return NaN;
      if (ratioStr.includes('/')) {
        const [w, h] = ratioStr.split('/').map(Number);
        return w / h;
      }
      return Number(ratioStr);
    },

    initModal() {
      const modalEl = document.getElementById('cropModal');
      if (!modalEl) return;
      
      this.modal = new bootstrap.Modal(modalEl, {
        backdrop: 'static',
        keyboard: false
      });

      modalEl.addEventListener('shown.bs.modal', () => {
        this.setupCropper();
      });

      modalEl.addEventListener('hidden.bs.modal', () => {
        this.destroyCropper();
      });
    },

    handleFileChange(id, event) {
      const file = event.target.files[0];
      if (!file) {
        if (this.groups[id]) this.groups[id].hasFile = false;
        return;
      }

      if (file.size > 5 * 1024 * 1024) {
        alert('Ukuran file terlalu besar. Maksimal 5MB.');
        event.target.value = '';
        return;
      }

      const reader = new FileReader();
      reader.onload = (e) => {
        this.imageToCropSrc = e.target.result;
        this.activeGroupId = id;
        this.groups[id].hasFile = true;
        
        setTimeout(() => {
          this.modal.show();
        }, 50);
      };
      reader.readAsDataURL(file);
    },

    openCropModal(id) {
      if (this.groups[id] && this.groups[id].hasFile) {
        this.activeGroupId = id;
        this.modal.show();
      }
    },

    setupCropper() {
      const imageEl = document.getElementById('imageToCrop');
      if (!imageEl || !this.activeGroupId) return;

      const group = this.groups[this.activeGroupId];
      
      if (this.cropper) {
        this.cropper.destroy();
      }

      this.cropper = new Cropper(imageEl, {
        aspectRatio: group.aspectRatio,
        viewMode: 1,
        dragMode: 'move',
        autoCropArea: 0.8,
        restore: false,
        guides: true,
        center: true,
        highlight: false,
        cropBoxMovable: true,
        cropBoxResizable: true,
        toggleDragModeOnDblclick: false,
      });
    },

    destroyCropper() {
      if (this.cropper) {
        this.cropper.destroy();
        this.cropper = null;
      }
    },

    applyCrop() {
      if (!this.cropper || !this.activeGroupId) return;

      const canvas = this.cropper.getCroppedCanvas({
        maxWidth: 2000,
        maxHeight: 2000,
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high',
      });

      const croppedData = canvas.toDataURL('image/jpeg', 0.85);
      const group = this.groups[this.activeGroupId];
      
      group.croppedData = croppedData;
      group.previewUrl = croppedData;

      this.modal.hide();
    },

    initFormData() {
      const scriptTag = document.getElementById('initial-form-data');
      if (scriptTag) {
        try {
          this.formData = JSON.parse(scriptTag.textContent);
        } catch (e) {
          console.error("Failed to parse initial form data", e);
        }
      }
    },

    // --- Validation Methods ---
    validateField(field, rules) {
      const value = this.formData[field];
      let errorMessage = '';

      const isEmpty = value === undefined || value === null || value.toString().trim() === '';

      if (rules.required && isEmpty) {
        errorMessage = 'Field ini wajib diisi.';
      } else if (!isEmpty) {
        if (rules.minLength && value.length < rules.minLength) {
          errorMessage = `Minimal ${rules.minLength} karakter.`;
        } else if (rules.minNum !== undefined && Number(value) < rules.minNum) {
          errorMessage = `Nilai minimal adalah ${rules.minNum}.`;
        } else if (rules.maxNum !== undefined && Number(value) > rules.maxNum) {
          errorMessage = `Nilai maksimal adalah ${rules.maxNum}.`;
        }
      }

      this.errors[field] = errorMessage;
      return errorMessage === '';
    },

    onFormSubmit(e) {
      // 1. Final Crop Validation
      let isCropValid = true;
      let firstMissingCrop = null;

      Object.keys(this.groups).forEach(id => {
        const group = this.groups[id];
        if (group.required && !group.croppedData) {
          isCropValid = false;
          if (!firstMissingCrop) firstMissingCrop = group;
        }
      });

      if (!isCropValid) {
        e.preventDefault();
        alert(`Harap lakukan crop pada ${firstMissingCrop.label} terlebih dahulu.`);
        return;
      }

      // 2. Final Field Validation (if any fields were missed)
      const hasErrors = Object.values(this.errors).some(err => err !== null && err !== '');
      if (hasErrors) {
        e.preventDefault();
        alert('Harap perbaiki kesalahan pada form sebelum menyimpan.');
      }
    }
  }
}).mount('#crop-app');
