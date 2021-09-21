<x-laramodal-modal />

@laramodalScripts

<script>
    window.laramodal = function () {
        return {
            modalElement : document.getElementById('x-modal'),
            component    : '',
            size         : null,
            heading      : 'loading . . .',
            subHeading   : '',
            ready        : false,

            eventData(event){
                return {
                    'component'  : event.detail.component,
                    'size'       : Object.prototype.hasOwnProperty.call(event.detail, 'size') ?? null,
                    'heading'    : event.detail.heading,
                    'subHeading' : event.detail.subHeading,
                };
            },

            onOpen(event) {
                this.component  = event.detail.component;
                this.size       = Object.prototype.hasOwnProperty.call(event.detail, 'size') ? event.detail.size : null;
                this.heading    = event.detail.heading;
                this.subHeading = event.detail.subHeading;
                this.ready      = false;

                new bootstrap.Modal(document.getElementById('x-modal')).show();

                window.livewire.emitTo('laramodal', 'openModal', event.detail.component, event.detail.args);
            },

            boot() {
                this.modalElement.addEventListener('hidden.bs.modal', function () {
                    window.livewire.emitTo('laramodal', 'closeModal');
                    this.ready = false;
                })
            },
        }
    }

    function _openModal(heading, subHeading, component, params = [], size = null) {
        window.dispatchEvent(new CustomEvent("open-x-modal", { detail: {
                component  : component,
                size       : size,
                heading    : heading,
                subHeading : subHeading,
                args       : params
            }}));
    }










    // let laramodal = window.laramodal;

    window.laramodal = function () {
        return {
            showActiveComponent : true,
            componentHistory    : [],
            activeComponent     : false,
            modalElement        : document.getElementById('x-modal'),
            modalTitle          : 'loading . . .',
            component           : '',
            ready               : false,
            size                : 'lg',

            getActiveComponentModalAttribute(key) {
                if(this.$wire.get('components')[this.activeComponent] !== undefined) {
                    return this.$wire.get('components')[this.activeComponent]['args'][key];
                }
            },

            closeModalOnEscape(trigger) {
                if(this.getActiveComponentModalAttribute('closeOnEscape') === false) {
                    return;
                }

                let force = this.getActiveComponentModalAttribute('closeOnEscapeIsForceful') === true;
                this.closeModal(force);
            },

            closeModalOnClickAway(trigger) {
                if(this.getActiveComponentModalAttribute('closeOnClickAway') === false) {
                    return;
                }

                this.closeModal(true);
            },

            closeModal(force = false, skipPreviousModals = 0) {

                if(this.getActiveComponentModalAttribute('dispatchCloseEvent') === true) {
                    const componentName = this.$wire.get('components')[this.activeComponent].name;
                    Livewire.emit('modalClosed', componentName);
                }

                if (skipPreviousModals > 0) {
                    for ( var i = 0; i < skipPreviousModals; i++ ) {
                        this.componentHistory.pop();
                    }
                }

                const id = this.componentHistory.pop();

                if (id && force === false) {
                    this.setActiveModalComponent(id, true);
                }

                this.bsCloseModal(this.activeComponent)

                this.ready = false;
            },

            setActiveModalComponent(id, skip = false) {
                this.ready = true;

                if (this.activeComponent !== false && skip === false) {
                    this.componentHistory.push(this.activeComponent);
                }

                let focusableTimeout = 50;

                if (this.activeComponent === false) {

                    this.componentAttributes(id)

                } else {
                    this.showActiveComponent = false;

                    focusableTimeout = 400;

                    setTimeout(() => {
                        this.componentAttributes(id)
                    }, 300);
                }

                this.$nextTick(() => {
                    let focusable = this.$refs[id].querySelector('[autofocus]');
                    if (focusable) {
                        setTimeout(() => { focusable.focus(); }, focusableTimeout);
                    }
                });

                this.modalTitle = this.getActiveComponentModalAttribute('bsTitle');

                this.bsOpenModal(id)

            },

            init() {
                this.$watch('show', value => {
                    if (value) {
                        document.body.classList.add('overflow-y-hidden');
                    } else {
                        document.body.classList.remove('overflow-y-hidden');

                        setTimeout( function () {
                            this.activeComponent = false;
                            this.$wire.resetState();
                        }, 300);
                    }
                });

                Livewire.on('closeModal', (force = false, skipPreviousModals = 0) => {
                    this.closeModal(force, skipPreviousModals);
                });

                Livewire.on('activeModalComponentChanged', (id) => {
                    this.setActiveModalComponent(id);
                });
            },

            componentAttributes(id) {
                this.showActiveComponent = true;
                this.activeComponent     = id
                this.size                = this.getActiveComponentModalAttribute('maxWidth');
            },

            bsModal(modal) {
                return document.getElementById(modal);
            },

            bsCloseModal(modal) {
                const backdrop = document.querySelector('.modal-backdrop.fade.show');

                this.bsModal(modal).setAttribute('aria-hidden', 'true');
                backdrop.classList.remove('show');

                setTimeout(() => {
                    this.bsModal(modal).classList.remove('show');
                });

                setTimeout( function () {
                    this.bsModal(modal).style.display = 'none';
                    backdrop.remove();
                }, 500);
            },

            bsOpenModal(modal) {
                const backdrop = document.createElement('div');
                backdrop.classList.add('modal-backdrop', 'fade');

                document.body.classList.add('modal-open');
                document.body.appendChild(backdrop);

                this.bsModal(modal).style.display = 'block';
                this.bsModal(modal).setAttribute('aria-hidden', 'false', 'show');

                setTimeout( function () {
                    this.bsModal(modal).classList.add('show');
                    backdrop.classList.add('show');
                });
            }

        };
    }










    /**
     function laramodal() {
        return {
            modalElement : document.getElementById('x-modal'),
            component    : '',
            heading      : 'loading . . .',
            size         : null,
            ready        : false,
            boot() {
                function modalClose() {
                    Livewire.emitTo('laramodal', 'closeModal');
                    this.ready = false;
                }

                this.modalElement.addEventListener("click", function() {
                    modalClose();
                });
            },
            onOpen(event) {
                this.component = event.detail.component;
                this.heading   = event.detail.title;
                this.size      = Object.prototype.hasOwnProperty.call(event.detail, 'size') ? event.detail.size : null;
                this.ready     = false;

                new bootstrap.Modal(this.modalElement).show();

                Livewire.emitTo('laramodal', 'showModal', event.detail.component, event.detail.args);
            },
            onClose(event) {
                this.component = event.detail.component;
                this.heading   = event.detail.title;
                this.size      = Object.prototype.hasOwnProperty.call(event.detail, 'size') ? event.detail.size : null;
                this.ready     = false;

                new bootstrap.Modal(this.modalElement).show();

                Livewire.emitTo('laramodal', 'showModal', event.detail.component, event.detail.args);
            }
        }
    }

     function _openModal(title, component, params = [], size = null) {
        window.dispatchEvent(new CustomEvent("open-x-modal", {
            detail : {
                component : component,
                size      : size,
                args      : params
            }
        }));
    }
     */
</script>