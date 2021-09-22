<div
    id="x-modal"
    class="modal fade"
    tabindex="-1"
    aria-hidden="true"
    role="dialog"
    aria-labelledby="modal"
>

    <div
        x-data="laramodal()"
        x-init="boot()"
        x-on:open-x-modal.window="onOpen($event)"
        x-on:modal-ready.window="ready = true"
        class="modal-dialog modal-dialog-centered mw-900px"
        :class="[size ? `modal-${size}` : '']" role="document"
    >
        <div class="modal-content">

            <div class="modal-header">
                <div class="d-flex flex-row align-items-center">
                    <!--begin::Modal title-->
                    <h2 class="modal-title me-4" x-text="heading"></h2>
                    <template x-if="!ready">
                        <div class="spinner-border spinner-border-sm text-dark ml-2" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </template>
                    <!--end::Modal title-->
                </div>

                <!--begin::Close-->
                <div class="btn btn-sm btn-icon btn-active-color-primary close" x-on:click="onClose($event)" data-bs-dismiss="modal" aria-label="Close">
                    <!--begin::Svg Icon | path: icons/duotone/Navigation/Close.svg-->
                    <span class="svg-icon svg-icon-1">
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
									<g transform="translate(12.000000, 12.000000) rotate(-45.000000) translate(-12.000000, -12.000000) translate(4.000000, 4.000000)" fill="#000000">
										<rect fill="#000000" x="0" y="7" width="16" height="2" rx="1" />
										<rect fill="#000000" opacity="0.5" transform="translate(8.000000, 8.000000) rotate(-270.000000) translate(-8.000000, -8.000000)" x="0" y="7" width="16" height="2" rx="1" />
									</g>
								</svg>
							</span>
                    <!--end::Svg Icon-->
                </div>
                <!--end::Close-->
            </div>

            <div id="x--modal-body-wrapper">
                <div x-show="!ready">
                    <!--begin::Modal body-->
                    <div class="modal-body py-lg-10 px-lg-10">
                        <div class="row text-center align-items-center justify-content-center">
                            <div class="col-md-12">
                                <div class="card mb-0 w-100">
                                    <div class="card-body" style="height:200px">
                                        <div class="--skleton"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Modal body-->
                </div>

                <div x-show="ready">
                    <!--begin::Modal body-->
                    <div class="modal-body py-lg-10 px-lg-10 bg-body">
                        <div wire:loading.remove>

                        </div>

                        {{-- progress ui --}}
                        <div class="row" id="progress-ui">
                            <div wire:loading class="col-md-12 w-100">
                                <div class="progress-line"></div>
                            </div>
                        </div>

                        @livewire('laramodal')

                    </div>

                    <!--end::Modal body-->
                </div>
            </div>
        </div>
    </div>
</div>