@laramodalCss
@laramodalJs

@livewire('laramodal')

<script type="application/javascript">
    window.laramodal = function () {
        return {
            showActiveComponent : true,
            activeComponent     : false,
            modalElement        : document.getElementById('x-modal'),
            size                : null,
            heading             : 'Loading . . .',
            subHeading          : '',
            ready               : false,

            getComponentAttributes(id) {
                this.showActiveComponent = true;
                this.activeComponent     = id

                this.size                = this.getActiveComponentModalAttribute('size');
                this.heading             = this.getActiveComponentModalAttribute('heading');
                this.subHeading          = this.getActiveComponentModalAttribute('subHeading');
            },

            getActiveComponentModalAttribute(key) {
                if(this.$wire.get('components')[this.activeComponent] !== undefined) {
                    return this.$wire.get('components')[this.activeComponent][key];
                }
            },

            showModal(id) {
                this.ready = true;

                if (this.activeComponent !== false) {
                    this.showActiveComponent = false;
                }

                this.getComponentAttributes(id);

                new bootstrap.Modal(this.modalElement).show();
            },


            closeModal() {

                if(this.getActiveComponentModalAttribute('dispatchCloseEvent') === true) {
                    const componentName = this.$wire.get('components')[this.activeComponent].name;
                    Livewire.emit('modalClosed', componentName);
                }

                (bootstrap.Modal.getInstance(this.modalElement)).hide();

                this.ready = false;
                alert('kisses')
            },

            boot() {

                this.$watch('ready', value => {
                    if (value) {
                        document.body.classList.add('overflow-y-hidden');
                    } else {
                        document.body.classList.remove('overflow-y-hidden');

                        setTimeout(function () {
                            this.activeComponent = false;
                            this.$wire.resetState();
                        }, 300);
                    }
                });

                Livewire.on('showModal', (id) => {
                    this.showModal(id);
                });

                Livewire.on('hideModal', function () {
                    this.closeModal();
                });

                this.modalElement.addEventListener('hidden.bs.modal', function () {
                    Livewire.emit('resetModal');
                    this.ready = false;
                })

            },
        }
    }

</script>
