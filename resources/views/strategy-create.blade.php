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
                                            <select id="trade_quantity" name="trade_quantity" class="form-select" aria-describedby="trade_quantityHelpBlock" required="required">
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
                                            <select id="number_of_trades" name="number_of_trades" class="form-select" aria-describedby="number_of_tradesHelpBlock" required="required">
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
                                            <select id="running_counts" name="running_counts" class="form-select" aria-describedby="running_countsHelpBlock">
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
                                    <div class="form-group row">
                                        <label for="max_stock_price" class="col-4 col-form-label">Max Stock Price</label>
                                        <div class="col-8">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div
                                                        class="input-group-text" style="display: inline-table">
                                                        <i class="fa fa-money"></i>
                                                    </div>
                                                </div>
                                                <input id="max_stock_price" name="max_stock_price" placeholder="500.00" type="text" class="form-control" required="required">
                                                <div class="valid-feedback">
                                                    Looks good!
                                                </div>
                                                <div class="invalid-feedback">
                                                    Please Specify a Maximum
                                                    Price To Pay Per Share.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="max_stops_allowed" class="col-4 col-form-label">Stops Allowed</label>
                                        <div class="col-8">
                                            <select id="max_stops_allowed" name="max_stops_allowed" aria-describedby="max_stops_allowedHelpBlock" required="required" class="form-select">
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
                                                Please Specify The Maximum
                                                Number of Stops Per Symbol
                                            </div>
                                            <span id="max_stops_allowedHelpBlock" class="form-text text-muted">The Number of Stops Allowed Before Trading Is Halted On This Symbol</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="change_quantity_after_stops" class="col-4 col-form-label">Change Quantity After Stops</label>
                                        <div class="col-8">
                                            <select id="change_quantity_after_stops" name="change_quantity_after_stops" aria-describedby="change_quantity_after_stopsHelpBlock" required="required" class="form-select">
                                                <option value="true">Yes</option>
                                                <option value="false">No</option>
                                            </select>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                            <div class="invalid-feedback">
                                                Please Specify If You Would
                                                Like To Change The
                                                Share Quantity
                                                After Getting Stopped
                                            </div>
                                            <span id="change_quantity_after_stopsHelpBlock" class="form-text text-muted">Do You Want To Change Trade Quantity If You Get Stopped Out?</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="quantity_after_stop" class="col-4 col-form-label">Quantity of Shares After Stop</label>
                                        <div class="col-8">
                                            <select id="quantity_after_stop" name="quantity_after_stop" aria-describedby="quantity_after_stopHelpBlock" required="required" class="form-select">
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
                                                Please Specify What Quantity
                                                To Trade Once You've Been
                                                Stopped
                                            </div>
                                            <span id="quantity_after_stopHelpBlock" class="form-text text-muted">The Quantity of Shares To Trade With If You Have Been Stopped Out</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="stop_price" class="col-4 col-form-label">Stop Price</label>
                                        <div class="col-8">
                                            <div class="input-group">
                                                <div class="input-group-prepend" style="display: inline-table">
                                                    <div class="input-group-text">-</div>
                                                </div>
                                                <input id="stop_price" name="stop_price" placeholder="0.80" type="text" class="form-control" aria-describedby="stop_priceHelpBlock" required="required">
                                                <div class="input-group-append">
                                                    <div
                                                        class="input-group-text" style="display: inline-table">
                                                        <i class="fa fa-hand-stop-o"></i>
                                                    </div>
                                                </div>
                                                <div class="valid-feedback">
                                                    Looks good!
                                                </div>
                                                <div class="invalid-feedback">
                                                    Please Specify a Stop Price
                                                </div>
                                            </div>
                                            <span id="stop_priceHelpBlock" class="form-text text-muted">Stop Price - The price in a stop order that triggers the creation of a market order. In the case of a Sell on Stop order, a market sell order is triggered when the market price reaches or falls below the stop price.</span>
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <div id="step-3" class="tab-pane" role="tabpanel" aria-labelledby="step-3">
                                <form id="form-3" class="row row-cols-1 ms-5 me-5 needs-validation" novalidate>
                                    <div class="form-group row">
                                        <label for="limit_price" class="col-4 col-form-label">Limit Price</label>
                                        <div class="col-8">
                                            <select id="limit_price" name="limit_price" class="form-select" aria-describedby="limit_priceHelpBlock">
                                                <option value="lastPrice">Last Price</option>
                                                <option value="bidPrice">Bid Price</option>
                                                <option value="askPrice">Ask Price</option>
                                            </select>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                            <div class="invalid-feedback">
                                                Please Select Which Price To
                                                Use As Your Price Basis
                                            </div>
                                            <span id="limit_priceHelpBlock" class="form-text text-muted">What Part Of The Quote Do You Want To Base Your Limit Price</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="limit_price_offset" class="col-4 col-form-label">Limit Price Offset</label>
                                        <div class="col-8">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div
                                                        class="input-group-text" style="display: inline-table">
                                                        <i class="fa fa-money"></i>
                                                    </div>
                                                </div>
                                                <input id="limit_price_offset" name="limit_price_offset" placeholder="0.25" type="text" aria-describedby="limit_price_offsetHelpBlock" required="required" class="form-control">
                                                <div class="valid-feedback">
                                                    Looks good!
                                                </div>
                                                <div class="invalid-feedback">
                                                    Please Specify An Offset
                                                    Amount. If You Don't Want
                                                    An Offset Enter: 0.00
                                                </div>
                                            </div>
                                            <span id="limit_price_offsetHelpBlock" class="form-text text-muted">Limit Price Offset</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="high_price_buffer" class="col-4 col-form-label">High Price Buffer</label>
                                        <div class="col-8">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div
                                                        class="input-group-text" style="display: inline-table">
                                                        <i class="fa fa-hand-stop-o"></i>
                                                    </div>
                                                </div>
                                                <input id="high_price_buffer" name="high_price_buffer" placeholder="0.50" type="text" class="form-control" aria-describedby="high_price_bufferHelpBlock">
                                                <div class="valid-feedback">
                                                    Looks good!
                                                </div>
                                                <div class="invalid-feedback">
                                                    Please Specify A Buffer
                                                    Amount or 0.00 For No Buffer
                                                </div>
                                            </div>
                                            <span id="high_price_bufferHelpBlock" class="form-text text-muted">Buffer From The High Price of The Day That Engine Should Not Place Trades At. Your Profit Target Must Be Less Than This Amount: HighPrice + Buffer > Limit Price + Profit</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="profit" class="col-4 col-form-label">Profit Amount</label>
                                        <div class="col-8">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div
                                                        class="input-group-text" style="display: inline-table">
                                                        <i class="fa fa-money"></i>
                                                    </div>
                                                </div>
                                                <input id="profit" name="profit" placeholder="0.10" type="text" class="form-control" aria-describedby="profitHelpBlock">
                                                <div class="valid-feedback">
                                                    Looks good!
                                                </div>
                                                <div class="invalid-feedback">
                                                    Please Specify How Much
                                                    Profit Per Share You Are
                                                    Targeting
                                                </div>
                                            </div>
                                            <span id="profitHelpBlock" class="form-text text-muted">How Much Profit To Per Share Before</span>
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <div id="step-4" class="tab-pane" role="tabpanel" aria-labelledby="step-4">

                                <form id="form-4" class="row row-cols-1 ms-5 me-5 needs-validation" novalidate>
                                    <div class="col">
                                        <div class="mb-3 text-muted">Please
                                            confirm your trade details</div>

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

                var dataToSend = {
                    "strategy_name": $('#strategy_name').val(),
                    "enabled": $('#enabled').val(),
                    "trade_quantity": $('#trade_quantity').val(),
                    "number_of_trades": $('#number_of_trades').val(),
                    "running_counts": $('#running_counts').val(),
                    "max_stock_price": $('#max_stock_price').val(),
                    "max_stops_allowed": $('#max_stops_allowed').val(),
                    "change_quantity_after_stops": $('#change_quantity_after_stops').val(),
                    "quantity_after_stop": $('#quantity_after_stop').val(),
                    "stop_price": $('#stop_price').val(),
                    "limit_price": $('#limit_price').val(),
                    "limit_price_offset": $('#limit_price_offset').val(),
                    "high_price_buffer": $('#high_price_buffer').val(),
                    "profit": $('#profit').val(),
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

                // myModal.show();
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

        });
    </script>


</x-app-layout>
