@laramodalCss
@laramodalJs

@livewire('laramodal')

<script type="application/javascript">
    window.laramodal = () => {
        return {
            showActiveComponent : true,
            activeComponent     : false,
            size                : null,
            heading             : 'Loading . . .',
            subHeading          : '',
            show                : false,

            getModalElement(){
                return document.getElementById('x-modal')
            },

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
                this.show = true;

                if (this.activeComponent !== false) {
                    this.showActiveComponent = false;
                }

                this.getComponentAttributes(id);

                new bootstrap.Modal(this.getModalElement()).show();
            },

            closeModal(id) {
                this.show = false;

                if(this.getActiveComponentModalAttribute('dispatchCloseEvent') === true) {
                    const componentName = this.$wire.get('components')[this.activeComponent].name;
                    window.livewire.emit('modalClosed', componentName);
                }

                setTimeout(() => {

                    (bootstrap.Modal.getInstance(this.getModalElement())).hide();

                    const backdrop = document.querySelector('.modal-backdrop.fade.show');

                    this.getModalElement(id).setAttribute('aria-hidden', 'true');
                    backdrop.classList.remove('show');

                    setTimeout(() => {
                        this.getModalElement(id).classList.remove('show');
                    });

                }, 20);
            },

            boot() {

                this.$watch('show', value => {
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

                Livewire.on('hideModal', () => {
                   this.closeModal();
                });

                this.getModalElement().addEventListener('hidden.bs.modal', () => {
                    Livewire.emit('resetModal');
                    this.show = false;
                })

            },
        }
    }

</script>
