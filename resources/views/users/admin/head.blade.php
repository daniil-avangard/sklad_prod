<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="dastone-profile">
                    <div class="row">
                        <div class="col-lg-4 align-self-center mb-3 mb-lg-0">
                            <div class="dastone-profile-main">
                                <div class="dastone-profile-main-pic">
                                    <img src="/assets/images/users/user-4.jpg" alt="" height="110"
                                        class="rounded-circle">
                                    <span class="dastone-profile_main-pic-change">
                                        <i class="fas fa-camera"></i>
                                    </span>
                                </div>
                                <div class="dastone-profile_user-detail">
                                    <h5 class="dastone-user-name"> {{ $user->surname }} {{ $user->first_name }}
                                        {{ $user->middle_name }}</h5>
                                    <p class="mb-0 dastone-user-name-post">{{ $user->position }}</p>
                                </div>
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-4 ms-auto align-self-center">
                            <ul class="list-unstyled personal-detail mb-0">
                                <li class=""><i class="ti ti-mobile me-2 text-secondary font-16 align-middle"></i>
                                    <b> Телефон </b> : +91 23456 78910
                                </li>
                                <li class="mt-2"><i class="ti ti-email text-secondary font-16 align-middle me-2"></i>
                                    <b> Email </b> : {{ $user->email }}
                                </li>
                                </li>
                            </ul>

                        </div><!--end col-->
                        <div class="col-lg-4 align-self-center">
                            {{-- <div class="row">
                                <div class="col-auto text-end border-end">
                                    <button type="button"
                                        class="btn btn-soft-primary btn-icon-circle btn-icon-circle-sm mb-2">
                                        <i class="fab fa-facebook-f"></i>
                                    </button>
                                    <p class="mb-0 fw-semibold">Facebook</p>
                                    <h4 class="m-0 fw-bold">25k <span
                                            class="text-muted font-12 fw-normal">Followers</span></h4>
                                </div><!--end col-->
                                <div class="col-auto">
                                    <button type="button"
                                        class="btn btn-soft-info btn-icon-circle btn-icon-circle-sm mb-2">
                                        <i class="fab fa-twitter"></i>
                                    </button>
                                    <p class="mb-0 fw-semibold">Twitter</p>
                                    <h4 class="m-0 fw-bold">58k <span
                                            class="text-muted font-12 fw-normal">Followers</span></h4>
                                </div><!--end col-->
                            </div><!--end row--> --}}
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end f_profile-->
            </div><!--end card-body-->
        </div> <!--end card-->
    </div><!--end col-->
</div><!--end row-->
