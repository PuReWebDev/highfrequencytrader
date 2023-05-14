<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Strategy Builder') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <section class="signup-step-container">
                        <div class="container">
                            <div class="row d-flex justify-content-center">
                                <div class="col-md-8">
                                    <div class="wizard">
                                        <div class="wizard-inner">
                                            <div class="connecting-line"></div>
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" aria-expanded="true"><span class="round-tab">1 </span> <i>Step 1</i></a>
                                                </li>
                                                <li role="presentation" class="disabled">
                                                    <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" aria-expanded="false"><span class="round-tab">2</span> <i>Step 2</i></a>
                                                </li>
                                                <li role="presentation" class="disabled">
                                                    <a href="#step3" data-toggle="tab" aria-controls="step3" role="tab"><span class="round-tab">3</span> <i>Step 3</i></a>
                                                </li>
                                                <li role="presentation" class="disabled">
                                                    <a href="#step4" data-toggle="tab" aria-controls="step4" role="tab"><span class="round-tab">4</span> <i>Step 4</i></a>
                                                </li>
                                            </ul>
                                        </div>

                                        <form role="form"
                                              method="post"
                                              action="/strategies"
                                              enctype="multipart/form-data"
                                              class="login-box">

                                            {{ csrf_field() }}

                                            <div class="tab-content" id="main_form">
                                                <div class="tab-pane active" role="tabpanel" id="step1">
                                                    <h4 class="text-center">Step 1</h4>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>First and Last Name *</label>
                                                                <input class="form-control" type="text" name="name" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Phone Number  *</label>
                                                                <input class="form-control" type="text" name="name" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Email Address *</label>
                                                                <input class="form-control" type="email" name="name" placeholder="">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Password *</label>
                                                                <input class="form-control" type="password" name="name" placeholder="">
                                                            </div>
                                                        </div>


                                                    </div>
                                                    <ul class="list-inline pull-right">
                                                        <li><button type="button" class="default-btn next-step">Continue to next steps</button></li>
                                                    </ul>
                                                </div>
                                                <div class="tab-pane" role="tabpanel" id="step2">
                                                    <h4 class="text-center">Step 2</h4>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Address 1 *</label>
                                                                <input class="form-control" type="text" name="name" placeholder="">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>City / Town *</label>
                                                                <input class="form-control" type="text" name="name" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Country *</label>
                                                                <select name="country" class="form-control" id="country">
                                                                    <option value="NG" selected="selected">Nigeria</option>
                                                                    <option value="NU">Niue</option>
                                                                    <option value="NF">Norfolk Island</option>
                                                                    <option value="KP">North Korea</option>
                                                                    <option value="MP">Northern Mariana Islands</option>
                                                                    <option value="NO">Norway</option>
                                                                </select>
                                                            </div>
                                                        </div>



                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Registration No.</label>
                                                                <input class="form-control" type="text" name="name" placeholder="">
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <ul class="list-inline pull-right">
                                                        <li><button type="button" class="default-btn prev-step">Back</button></li>
                                                        <li><button type="button" class="default-btn next-step skip-btn">Skip</button></li>
                                                        <li><button type="button" class="default-btn next-step">Continue</button></li>
                                                    </ul>
                                                </div>
                                                <div class="tab-pane" role="tabpanel" id="step3">
                                                    <h4 class="text-center">Step 3</h4>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Account Name *</label>
                                                                <input class="form-control" type="text" name="name" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Demo</label>
                                                                <input class="form-control" type="text" name="name" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Inout</label>
                                                                <input class="form-control" type="text" name="name" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Information</label>
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input" id="customFile">
                                                                    <label class="custom-file-label" for="customFile">Select file</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Number *</label>
                                                                <input class="form-control" type="text" name="name" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Input Number</label>
                                                                <input class="form-control" type="text" name="name" placeholder="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <ul class="list-inline pull-right">
                                                        <li><button type="button" class="default-btn prev-step">Back</button></li>
                                                        <li><button type="button" class="default-btn next-step skip-btn">Skip</button></li>
                                                        <li><button type="button" class="default-btn next-step">Continue</button></li>
                                                    </ul>
                                                </div>
                                                <div class="tab-pane" role="tabpanel" id="step4">
                                                    <h4 class="text-center">Step 4</h4>
                                                    <div class="all-info-container">
                                                        <div class="list-content">
                                                            <a href="#listone" data-toggle="collapse" aria-expanded="false" aria-controls="listone">Collapse 1 <i class="fa fa-chevron-down"></i></a>
                                                            <div class="collapse" id="listone">
                                                                <div class="list-box">
                                                                    <div class="row">

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>First and Last Name *</label>
                                                                                <input class="form-control" type="text"  name="name" placeholder="" disabled="disabled">
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>Phone Number *</label>
                                                                                <input class="form-control" type="text" name="name" placeholder="" disabled="disabled">
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="list-content">
                                                            <a href="#listtwo" data-toggle="collapse" aria-expanded="false" aria-controls="listtwo">Collapse 2 <i class="fa fa-chevron-down"></i></a>
                                                            <div class="collapse" id="listtwo">
                                                                <div class="list-box">
                                                                    <div class="row">

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>Address 1 *</label>
                                                                                <input class="form-control" type="text" name="name" placeholder="" disabled="disabled">
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>City / Town *</label>
                                                                                <input class="form-control" type="text" name="name" placeholder="" disabled="disabled">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>Country *</label>
                                                                                <select name="country2" class="form-control" id="country2" disabled="disabled">
                                                                                    <option value="NG" selected="selected">Nigeria</option>
                                                                                    <option value="NU">Niue</option>
                                                                                    <option value="NF">Norfolk Island</option>
                                                                                    <option value="KP">North Korea</option>
                                                                                    <option value="MP">Northern Mariana Islands</option>
                                                                                    <option value="NO">Norway</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>



                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>Legal Form</label>
                                                                                <select name="legalform2" class="form-control" id="legalform2" disabled="disabled">
                                                                                    <option value="" selected="selected">-Select an Answer-</option>
                                                                                    <option value="AG">Limited liability company</option>
                                                                                    <option value="GmbH">Public Company</option>
                                                                                    <option value="GbR">No minimum capital, unlimited liability of partners, non-busines</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>Business Registration No.</label>
                                                                                <input class="form-control" type="text" name="name" placeholder="" disabled="disabled">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>Registered</label>
                                                                                <select name="vat2" class="form-control" id="vat2" disabled="disabled">
                                                                                    <option value="" selected="selected">-Select an Answer-</option>
                                                                                    <option value="yes">Yes</option>
                                                                                    <option value="no">No</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>Seller</label>
                                                                                <input class="form-control" type="text" name="name" placeholder="" disabled="disabled">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label>Company Name *</label>
                                                                                <input class="form-control" type="password" name="name" placeholder="" disabled="disabled">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="list-content">
                                                            <a href="#listthree" data-toggle="collapse" aria-expanded="false" aria-controls="listthree">Collapse 3 <i class="fa fa-chevron-down"></i></a>
                                                            <div class="collapse" id="listthree">
                                                                <div class="list-box">
                                                                    <div class="row">

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>Name *</label>
                                                                                <input class="form-control" type="text" name="name" placeholder="">
                                                                            </div>
                                                                        </div>


                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>Number *</label>
                                                                                <input class="form-control" type="text" name="name" placeholder="">
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <ul class="list-inline pull-right">
                                                        <li><button type="button" class="default-btn prev-step">Back</button></li>
                                                        <li><button type="button" class="default-btn next-step">Finish</button></li>
                                                    </ul>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </div>

            </div>
        </div>
    </div>

    <script type="text/javascript">
        // ------------step-wizard-------------
        $(document).ready(function () {
            // $('.nav-tabs > li a[title]').tooltip();

            //Wizard
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

                var target = $(e.target);

                if (target.parent().hasClass('disabled')) {
                    return false;
                }
            });

            $(".next-step").click(function (e) {

                var active = $('.wizard .nav-tabs li.active');
                active.next().removeClass('disabled');
                nextTab(active);

            });
            $(".prev-step").click(function (e) {

                var active = $('.wizard .nav-tabs li.active');
                prevTab(active);

            });
        });

        function nextTab(elem) {
            $(elem).next().find('a[data-toggle="tab"]').click();
        }
        function prevTab(elem) {
            $(elem).prev().find('a[data-toggle="tab"]').click();
        }


        $('.nav-tabs').on('click', 'li', function() {
            $('.nav-tabs li.active').removeClass('active');
            $(this).addClass('active');
        });
    </script>


</x-app-layout>
