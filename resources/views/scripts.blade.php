<x-laramodal-modal />

@laramodalScripts

<script>
    function laramodal() {
        return {
            modalElement : document.getElementById('x-modal'),
            modal        : '',
            size         : null,
            heading      : 'loading . . .',
            subHeading   : '',
            ready        : false,

            eventData(event){
                return {
                    'modal'      : event.detail.modal,
                    'size'       : Object.prototype.hasOwnProperty.call(event.detail, 'size') ?? null,
                    'heading'    : event.detail.heading,
                    'subHeading' : event.detail.subHeading,
                };
            },

            onOpen(event) {
                this.modal      = event.detail.modal;
                this.size       = Object.prototype.hasOwnProperty.call(event.detail, 'size') ? event.detail.size : null;
                this.heading    = 'event.detail.heading';
                this.subHeading = event.detail.subHeading;
                this.ready      = false;

                new bootstrap.Modal(this.modalElement).show();
console.log(event.detail.modal)
                window.livewire.emitTo('laramodal', 'openModal', event.detail.modal, event.detail.args);
            },

            boot() {
                (this.modalElement).addEventListener('hidden.bs.modal', function () {
                    window.livewire.emitTo('laramodal', 'closeModal');
                    this.ready = false;
                })
            },
        }
    }

    function _openModal(heading, subHeading, modal, args = [], size = null) {
        window.dispatchEvent(new CustomEvent("open-x-modal", { detail: {
                modal      : modal,
                size       : size,
                heading    : heading,
                subHeading : subHeading,
                args       : args
            }}));
    }

</script>
