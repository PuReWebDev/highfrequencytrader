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

                    <!-- SmartWizard html -->
                    <div id="smartwizard" dir="rtl-">
                        <ul class="nav nav-progress">
                            <li class="nav-item">
                                <a class="nav-link" href="#step-1">
                                    <div class="num">1</div>
                                    Trade Details
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#step-2">
                                    <span class="num">2</span>
                                    Risk Management
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#step-3">
                                    <span class="num">3</span>
                                    Trade Entry/Exit Details
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="#step-4">
                                    <span class="num">4</span>
                                    Confirm Trade
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">

                            <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">

                                <form id="form-1" method="post" action="/strategies"
                                      enctype="multipart/form-data" class="row row-cols-1 ms-5
                                 me-5 needs-validation" novalidate>
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <label for="strategy_name" class="col-4 col-form-label">Strategy Name</label>
                                        <div class="col-8">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text" style="display: inline-table">
                                                        <i class="fa fa-lightbulb-o"></i>
                                                    </div>
                                                </div>
                                                <input id="strategy_name" name="strategy_name" type="text" class="form-control" aria-describedby="strategy_nameHelpBlock" required="required">
                                                <div class="valid-feedback">
                                                    Looks good!
                                                </div>
                                                <div class="invalid-feedback">
                                                    Please Provide A Name For
                                                    Your Strategy.
                                                </div>
                                            </div>
                                            <span id="strategy_nameHelpBlock" class="form-text text-muted">The Name Can Be Anything You Choose</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="enabled" class="col-4 col-form-label">Enabled For Trading</label>
                                        <div class="col-8">
                                            <select id="enabled" name="enabled" class="form-select" aria-describedby="enabledHelpBlock" required="required">
                                                <option value="true">Enabled</option>
                                                <option value="false">Disbled</option>
                                            </select>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                            <div class="invalid-feedback">
                                                Please Enable or Disable
                                            </div>
                                            <span id="enabledHelpBlock" class="form-text text-muted">Enabled For Trading During Trading Hours</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="trade_quantity" class="col-4 col-form-label">Quantity of Shares Per Trade</label>
                                        <div class="col-8">
                                            <select id="trade_quantity" name="trade_quantity" class="custom-select" aria-describedby="trade_quantityHelpBlock" required="required">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                            </select>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                            <div class="invalid-feedback">
                                                Please Select The Number of
                                                Shares Per Trade
                                            </div>
                                            <span id="trade_quantityHelpBlock" class="form-text text-muted">How Many Individual Shares Should Be Purchased In Each Individual Trade.</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="number_of_trades" class="col-4 col-form-label">How Many Trades To Perform</label>
                                        <div class="col-8">
                                            <select id="number_of_trades" name="number_of_trades" class="custom-select" aria-describedby="number_of_tradesHelpBlock" required="required">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">I0</option>
                                                <option value="20">20</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                                <option value="200">200</option>
                                                <option value="500">500</option>
                                                <option value="1000">1000</option>
                                                <option value="1500">1500</option>
                                                <option value="2000">2000</option>
                                            </select>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                            <div class="invalid-feedback">
                                                Please Select The Number of
                                                Trades To Attempt
                                            </div>
                                            <span id="number_of_tradesHelpBlock" class="form-text text-muted">Limit Trading After Total Number Of Trades</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="running_counts" class="col-4 col-form-label">Number Of Concurrent Trades</label>
                                        <div class="col-8">
                                            <select id="running_counts" name="running_counts" class="custom-select" aria-describedby="running_countsHelpBlock">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                            </select>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                            <div class="invalid-feedback">
                                                Select The Number of Trades
                                                To Run At The Same Time
                                            </div>
                                            <span id="running_countsHelpBlock" class="form-text text-muted">The Total Number Of Buys/Sales Total At A Time</span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">
                                <form id="form-2" class="row row-cols-1 ms-5 me-5 needs-validation" novalidate>
                                    <div class="col-md-6">
                                        <label for="validationCustom04" class="form-label">Product</label>
                                        <select class="form-select" id="sel-products" multiple required>
                                            <option value="Apple iPhone 13">Apple iPhone 13</option>
                                            <option value="Apple iPhone 12">Apple iPhone 12</option>
                                            <option value="Samsung Galaxy S10">Samsung Galaxy S10</option>
                                            <option value="Motorola G5">Motorola G5</option>
                                        </select>
                                        <div class="valid-feedback">
                                            Looks good!
                                        </div>
                                        <div class="invalid-feedback">
                                            Please select product.
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <div id="step-3" class="tab-pane" role="tabpanel" aria-labelledby="step-3">
                                <form id="form-3" class="row row-cols-1 ms-5 me-5 needs-validation" novalidate>
                                    <div class="col">
                                        <label for="address" class="form-label">Address</label>
                                        <input type="text" class="form-control" id="address" placeholder="1234 Main St" required="">
                                        <div class="invalid-feedback">
                                            Please enter your shipping address.
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label for="validationCustom04" class="form-label">State</label>
                                        <select class="form-select" id="state" required>
                                            <option selected disabled value="">Choose...</option>
                                            <option>State 1</option>
                                            <option>State 2</option>
                                            <option>State 3</option>
                                        </select>
                                        <div class="valid-feedback">
                                            Looks good!
                                        </div>
                                        <div class="invalid-feedback">
                                            Please select a valid state.
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label for="validationCustom05" class="form-label">Zip</label>
                                        <input type="text" class="form-control" id="zip" required>
                                        <div class="invalid-feedback">
                                            Please provide a valid zip.
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <div id="step-4" class="tab-pane" role="tabpanel" aria-labelledby="step-4">

                                <form id="form-4" class="row row-cols-1 ms-5 me-5 needs-validation" novalidate>
                                    <div class="col">
                                        <div class="mb-3 text-muted">Please confirm your order details</div>

                                        <div id="trade-summary"></div>

                                        <h4 class="mt-3">Confirmation</h4>
                                        <hr class="my-2">

                                        <div class="row gy-3">


                                            <div class="col">
                                                <input type="checkbox" class="form-check-input" id="save-info" required>
                                                <label class="form-check-label" for="save-info">I agree to the terms and conditions</label>
                                            </div>


                                        </div>
                                    </div>
                                </form>



                            </div>
                            </form>
                        </div>

                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <br /> &nbsp;

                    <!-- Confirm Modal -->
                    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmModalLabel">Order Placed</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Your Trade Strategy Has Been Created
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" onclick="closeModal()">Ok, close and reset</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <script type="text/javascript">

        const myModal = new bootstrap.Modal(document.getElementById('confirmModal'));


        function onCancel() {
            // Reset wizard
            $('#smartwizard').smartWizard("reset");

            // Reset form
            document.getElementById("form-1").reset();
            document.getElementById("form-2").reset();
            document.getElementById("form-3").reset();
            document.getElementById("form-4").reset();
        }

        function onConfirm() {
            let form = document.getElementById('form-4');
            if (form) {
                if (!form.checkValidity()) {
                    form.classList.add('was-validated');
                    $('#smartwizard').smartWizard("setState", [3], 'error');
                    $("#smartwizard").smartWizard('fixHeight');
                    return false;
                }

                myModal.show();
            }
        }

        function closeModal() {
            // Reset wizard
            var dataToSend = {
                "strategy_name": $('#strategy_name').val(),
                "enabled": $('#enabled').val(),
                "trade_quantity": $('#trade_quantity').val(),
                "number_of_trades": $('#number_of_trades').val(),
                "running_counts": $('#running_counts').val()
            };

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });

            jQuery.ajax({
                type: 'POST',
                url: "/strategies",
                data: JSON.stringify(dataToSend),
                dataType: "json",
                success: function(data){ window.location.replace("https://highfrequencytradingservices.com/strategies"); }
            });
            // $("form").submit();
            $('#smartwizard').smartWizard("reset");

            // Reset form
            document.getElementById("form-1").reset();
            document.getElementById("form-2").reset();
            document.getElementById("form-3").reset();
            document.getElementById("form-4").reset();

            myModal.hide();
        }

        function showConfirm() {
            const strategy_name = $('#strategy_name').val();
            const trade_quantity = $('#trade_quantity').val();
            const enabled = $('#enabled').val();
            const products = $('#sel-products').val();
            const shipping = $('#address').val() + ' ' + $('#state').val() + ' ' + $('#zip').val();
            let html = `<h4 class="mb-3-">Trade Details</h4>
                  <hr class="my-2">
                  <div class="row g-3 align-items-center">
                    <div class="col-auto">
                      <label class="col-form-label">Strategy Name</label>
                    </div>
                    <div class="col-auto">
                      <span class="form-text-">${strategy_name}</span>
                    </div>
                  </div>

                  <h4 class="mt-3">Enabled</h4>
                  <hr class="my-2">
                  <div class="row g-3 align-items-center">
                    <div class="col-auto">
                      <span class="form-text-">${enabled}</span>
                    </div>
                  </div>

                  <h4 class="mt-3">Trade Quantity</h4>
                  <hr class="my-2">
                  <div class="row g-3 align-items-center">
                    <div class="col-auto">
                      <span class="form-text-">${trade_quantity}</span>
                    </div>
                  </div>`;
            $("#trade-summary").html(html);
            $('#smartwizard').smartWizard("fixHeight");
        }

        $(function() {
            // Leave step event is used for validating the forms
            $("#smartwizard").on("leaveStep", function(e, anchorObject, currentStepIdx, nextStepIdx, stepDirection) {
                // Validate only on forward movement
                if (stepDirection == 'forward') {
                    let form = document.getElementById('form-' + (currentStepIdx + 1));
                    if (form) {
                        if (!form.checkValidity()) {
                            form.classList.add('was-validated');
                            $('#smartwizard').smartWizard("setState", [currentStepIdx], 'error');
                            $("#smartwizard").smartWizard('fixHeight');
                            return false;
                        }
                        $('#smartwizard').smartWizard("unsetState", [currentStepIdx], 'error');
                    }
                }
            });

            // Step show event
            $("#smartwizard").on("showStep", function(e, anchorObject, stepIndex, stepDirection, stepPosition) {
                $("#prev-btn").removeClass('disabled').prop('disabled', false);
                $("#next-btn").removeClass('disabled').prop('disabled', false);
                if(stepPosition === 'first') {
                    $("#prev-btn").addClass('disabled').prop('disabled', true);
                } else if(stepPosition === 'last') {
                    $("#next-btn").addClass('disabled').prop('disabled', true);
                } else {
                    $("#prev-btn").removeClass('disabled').prop('disabled', false);
                    $("#next-btn").removeClass('disabled').prop('disabled', false);
                }

                // Get step info from Smart Wizard
                let stepInfo = $('#smartwizard').smartWizard("getStepInfo");
                $("#sw-current-step").text(stepInfo.currentStep + 1);
                $("#sw-total-step").text(stepInfo.totalSteps);

                if (stepPosition == 'last') {
                    showConfirm();
                    $("#btnFinish").prop('disabled', false);
                } else {
                    $("#btnFinish").prop('disabled', true);
                }

                // Focus first name
                if (stepIndex == 1) {
                    setTimeout(() => {
                        $('#strategy_name').focus();
                    }, 0);
                }
            });

            // Smart Wizard
            $('#smartwizard').smartWizard({
                selected: 0,
                // autoAdjustHeight: false,
                theme: 'arrows', // basic, arrows, square, round, dots
                transition: {
                    animation:'none'
                },
                toolbar: {
                    showNextButton: true, // show/hide a Next button
                    showPreviousButton: true, // show/hide a Previous button
                    position: 'bottom', // none/ top/ both bottom
                    extraHtml: `<button class="btn btn-success"
                    id="btnFinish" disabled onclick="onConfirm()">Complete Strategy</button>
                              <button class="btn btn-danger" id="btnCancel" onclick="onCancel()">Cancel</button>`
                },
                anchor: {
                    enableNavigation: true, // Enable/Disable anchor navigation
                    enableNavigationAlways: false, // Activates all anchors clickable always
                    enableDoneState: true, // Add done state on visited steps
                    markPreviousStepsAsDone: true, // When a step selected by url hash, all previous steps are marked done
                    unDoneOnBackNavigation: true, // While navigate back, done state will be cleared
                    enableDoneStateNavigation: true // Enable/Disable the done state navigation
                },
            });

            $("#state_selector").on("change", function() {
                $('#smartwizard').smartWizard("setState", [$('#step_to_style').val()], $(this).val(), !$('#is_reset').prop("checked"));
                return true;
            });

            $("#style_selector").on("change", function() {
                $('#smartwizard').smartWizard("setStyle", [$('#step_to_style').val()], $(this).val(), !$('#is_reset').prop("checked"));
                return true;
            });

        });
    </script>


</x-app-layout>
