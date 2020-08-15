<div class="print-only receipt not-pos" ko="with: selected">
                        	<div>
                            	<div style="display: inline-block;">
	                        		<h2 style="font-weight: bold; margin: 0 0 5px">RECEIPT</h2>
                                    <div class="text-uppercase small" style="padding: 0 2px">
                                    	<div>Date:&emsp;<span ko="text: $root.todays_date()">08/01/16 (12:56 am)</span></div>
                                        <div>Order No:&emsp;<span ko="text: job_id">040116-1</span></div>
                                        <div ko="if: (where=='pending-jobs')">Balance:&emsp;&#8358;<span ko="text: addCommas(balance)">040116-1</span></div>
                                    </div>
                                </div>
                                <flex class="pull-right col-sm-5">
                                	<img class="img img-responsive" style="float: left; height: 60px; width: 40px; margin-right: 3px" src="<? echo $x_link_prefix ?>images/full_logo.jpg" />
                                    <div>
                                        <small style="font-weight: bold" class="text-uppercase">Charvid Digital Press</small>
                                        <div class="small text-justify">
                                            <div style="width: 80%">
                                            	<div>Suite C201, First Floor,</div>
                                                <div>Soar Plaza, Plot C120, Road 521 by 522,</div>
                                                First Avenue, Gwarimpa, Abuja.</div>
                                            <div style="font-weight:normal" class="text-left small">
                                            	<tt>+234-09-291-6828 | +234(0)8139331462 +234(0)81836396</tt>
                                            </div>
                                        </div>
                                    </div>
                                </flex>
                                <div class="clearfix"></div>
                                <div style="padding: 1rem 2px">
                                	<div class="print-vertically-mini-padded">
                                    	<strong>Received from: </strong>
                                        <span style="border-bottom: thin dotted #05a; padding-right: 1.5rem" ko="text: client_name+(client_phone != ''? ' ('+client_phone+')':'')">...</span>
                                    </div>
                                    <div class="print-vertically-mini-padded">
	                                	<strong>The sum of: </strong>
                                        <span style="border-bottom: thin dotted #05a; padding-right: 1.5rem" ko="text: numberToEnglish(paid_whole_part)+' naira, '+numberToEnglish(paid_decimal_part)+' kobo only.'"></span>
                                    </div>
                                    <div class="print-vertically-mini-padded">
                                    	<strong>Being <span ko="text: where=='completed-jobs'? 'FULL':'PART'">...</span> payment for: </strong>
                                        <span style="border-bottom: thin dotted #05a; padding-right: 1.5rem" ko="text: job_description">..</span>
                                    </div>
                                    <div class="print-vertically-mini-padded col-sm-12 row" style="padding-top: 1rem">
                                    	<div class="pull-left">
                                    	<strong class="">&#8358;&emsp;<span ko="text: addCommas(paid_whole_part)"></span> : <span ko="text: paid_decimal_part"></span>&emsp;k</strong>
                                        <div class="small">5% VAT incl.</div>
                                        </div>
                                        <div class="pull-right">	&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;
                                        	<div class="text-right">Staff Signature</div>
                                        </div>
                                        <div class="pull-right">	&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&emsp;
                                        	<div class="text-right">Customer Signature&emsp;</div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Customer copy -->
                            <hr style="border: dashed 1.3px; margin: 1rem 0" />
                            <!-- Separator -->
                            <div>
                            	<div style="display: inline-block;">
	                        		<h2 style="font-weight: bold; margin: 0 0 5px">RECEIPT</h2>
                                    <div class="text-uppercase small" style="padding: 0 2px">
                                    	<div>Date:&emsp;<span ko="text: $root.todays_date()">08/01/16 (12:56 am)</span></div>
                                        <div>Order No:&emsp;<span ko="text: job_id">040116-1</span></div>
                                        <div ko="if: (where=='pending-jobs')">Balance:&emsp;&#8358;<span ko="text: addCommas(balance)">040116-1</span></div>
                                    </div>
                                </div>
                                <flex class="pull-right col-sm-5">
                                	<img class="img img-responsive" style="float: left; height: 60px; width: 40px; margin-right: 3px" src="<? echo $x_link_prefix ?>images/full_logo.jpg" />
                                    <div>
                                        <small style="font-weight: bold" class="text-uppercase">Charvid Digital Press</small>
                                        <div class="small text-justify">
                                            <div style="width: 80%">
                                            	<div>Suite C201, First Floor,</div>
                                                <div>Soar Plaza, Plot C120, Road 521 by 522,</div>
                                                First Avenue, Gwarimpa, Abuja.</div>
                                            <div style="font-weight:normal" class="text-left small">
                                            	<tt>+234-09-291-6828 | +234(0)8139331462 +234(0)81836396</tt>
                                            </div>
                                        </div>
                                    </div>
                                </flex>
                                <div class="clearfix"></div>
                                <div style="padding: 1rem 2px">
                                	<div class="print-vertically-mini-padded">
                                    	<strong>Received from: </strong>
                                        <span style="border-bottom: thin dotted #05a; padding-right: 1.5rem" ko="text: client_name+(client_phone != ''? ' ('+client_phone+')':'')">...</span>
                                    </div>
                                    <div class="print-vertically-mini-padded">
	                                	<strong>The sum of: </strong>
                                        <span style="border-bottom: thin dotted #05a; padding-right: 1.5rem" ko="text: numberToEnglish(paid_whole_part)+' naira, '+numberToEnglish(paid_decimal_part)+' kobo only.'"></span>
                                    </div>
                                    <div class="print-vertically-mini-padded">
                                    	<strong>Being <span ko="text: where=='completed-jobs'? 'FULL':'PART'">...</span> payment for: </strong>
                                        <span style="border-bottom: thin dotted #05a; padding-right: 1.5rem" ko="text: job_description">..</span>
                                    </div>
                                    <div class="print-vertically-mini-padded col-sm-12 row" style="padding-top: 1rem">
                                    	<div class="pull-left">
                                    	<strong class="">&#8358;&emsp;<span ko="text: addCommas(paid_whole_part)"></span> : <span ko="text: paid_decimal_part"></span>&emsp;k</strong>
                                        <div class="small">5% VAT incl.</div>
                                        </div>
                                        <div class="pull-right">	&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;
                                        	<div class="text-right">Staff Signature</div>
                                        </div>
                                        <div class="pull-right">	&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&emsp;
                                        	<div class="text-right">Customer Signature&emsp;</div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Company Copy -->
                            <hr style="border: dashed 1.3px; margin: 1rem 0" />
                            <!-- Separator -->
                            <div>
                            	<div style="display: inline-block;">
	                        		<h2 style="font-weight: bold; margin: 0 0 5px">RECEIPT</h2>
                                    <div class="text-uppercase small" style="padding: 0 2px">
                                    	<div>Date:&emsp;<span ko="text: $root.todays_date()">08/01/16 (12:56 am)</span></div>
                                        <div>Order No:&emsp;<span ko="text: job_id">040116-1</span></div>
                                        <div ko="if: (where=='pending-jobs')">Balance:&emsp;&#8358;<span ko="text: addCommas(balance)">040116-1</span></div>
                                    </div>
                                </div>
                                <flex class="pull-right col-sm-5">
                                	<img class="img img-responsive" style="float: left; height: 60px; width: 40px; margin-right: 3px" src="<? echo $x_link_prefix ?>images/full_logo.jpg" />
                                    <div>
                                        <small style="font-weight: bold" class="text-uppercase">Charvid Digital Press</small>
                                        <div class="small text-justify">
                                            <div style="width: 80%">
                                            	<div>Suite C201, First Floor,</div>
                                                <div>Soar Plaza, Plot C120, Road 521 by 522,</div>
                                                First Avenue, Gwarimpa, Abuja.</div>
                                            <div style="font-weight:normal" class="text-left small">
                                            	<tt>+234-09-291-6828 | +234(0)8139331462 +234(0)81836396</tt>
                                            </div>
                                        </div>
                                    </div>
                                </flex>
                                <div class="clearfix"></div>
                                <div style="padding: 1rem 2px">
                                	<div class="print-vertically-mini-padded">
                                    	<strong>Received from: </strong>
                                        <span style="border-bottom: thin dotted #05a; padding-right: 1.5rem" ko="text: client_name+(client_phone != ''? ' ('+client_phone+')':'')">...</span>
                                    </div>
                                    <div class="print-vertically-mini-padded">
	                                	<strong>The sum of: </strong>
                                        <span style="border-bottom: thin dotted #05a; padding-right: 1.5rem" ko="text: numberToEnglish(paid_whole_part)+' naira, '+numberToEnglish(paid_decimal_part)+' kobo only.'"></span>
                                    </div>
                                    <div class="print-vertically-mini-padded">
                                    	<strong>Being <span ko="text: where=='completed-jobs'? 'FULL':'PART'">...</span> payment for: </strong>
                                        <span style="border-bottom: thin dotted #05a; padding-right: 1.5rem" ko="text: job_description">..</span>
                                    </div>
                                    <div class="print-vertically-mini-padded col-sm-12 row" style="padding-top: 1rem">
                                    	<div class="pull-left">
                                    	<strong class="">&#8358;&emsp;<span ko="text: addCommas(paid_whole_part)"></span> : <span ko="text: paid_decimal_part"></span>&emsp;k</strong>
                                        <div class="small">5% VAT incl.</div>
                                        </div>
                                        <div class="pull-right">	&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;
                                        	<div class="text-right">Staff Signature</div>
                                        </div>
                                        <div class="pull-right">	&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&emsp;
                                        	<div class="text-right">Customer Signature&emsp;</div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Third Copy -->
                        </div>                        
                        <!-- /receipt -->
                        
                        <div class="print-only receipt pos" ko="with: selected">
                        	<h4>Charvid Press</h4>
                            RECEIPT
                            <div class="pos-receipt-block">
                            	<p>Sub-total <span class="right">&#8358;<span ko="text: addCommas(final_cost)"></span></span></p>
                            	<p>Total <span class="right">&#8358;<span ko="text: addCommas(final_cost)"></span></span></p>
                            </div>
                            <div class="pos-receipt-block">
                            	<p>Paid <span class="right">&#8358;<span ko="text: addCommas(reimbursement)"></span></span></p>
                            	<p>Balance <span class="right">&#8358;<span ko="text: addCommas(balance)"></span></span></p>
                            </div>
                            <div class="pos-receipt-block">
                            	<p>Order no: <span ko="text: job_id"></span></p>
                            	<p>Customer: <span ko="text: client_name+(client_phone != ''? ' ('+client_phone+')':'')"></span></p>
                                <p>Description: <span ko="text: job_description"></span></p>
                            </div>
                            <div class="text-center" ko="text: $root.todays_date()"></div>
                        </div>
                        <!-- POS -->