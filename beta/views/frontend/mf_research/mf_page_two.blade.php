@extends('layouts.frontend')

@section('content')
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">Investment Analysis</h2>
                </div>
            </div>
        </div>

    </div>
    <section class="main-sec">
        <div class="container">
            <div style="border-bottom: 1px solid #ddd;">
                @include('frontend.mf_scanner.top_sidebar')
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="rt-pnl" style="box-shadow: none; padding-left: 0px; padding-right: 0px;">
                        <!-- <div class="rt-btn-prt">
                            <a href="{{route('frontend.samplereports')}}" target="_blank">Sample Reports</a>
                            <a href="{{route('frontend.how-to-use-calculator')}}" target="_blank">How to Use</a>
                        </div> -->
                        <p class="investmentOptionTitle">Select Investment Amount or Scheme</p>
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-4">
                                <label class="investmentOption investmentAmount activeInvestment"> <div class="investmentOptionText">Investment Amount</div>
                                  <input type="radio" name="radio">
                                  <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="col-lg-4">
                                <label class="investmentOption investmentScheme"> <div class="investmentOptionText">Scheme</div>
                                  <input type="radio" name="radio">
                                  <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="col-lg-2"></div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-3"></div>
                            <div class="col-lg-6">
                                <div class="form-inline investmentAmountForm">
                                    <label for="number" class="mb-0">Enter Investment Amount</label>
                                    <input type="number" class="form-control" id="number" placeholder="100000" name="number">
                                </div>
                            </div>
                            <div class="col-lg-3"></div>
                        </div>
                        
                        <div class="schemeSelect">
                            <div class="row">
                                <div class="col-lg-3"></div>
                                <div class="col-lg-6">    
                                    <div class="table-responsive">
                                        <table class="table schemeAlloTable mb-0">
                                        <thead>
                                          <tr>
                                            <th>Scheme</th>
                                            <th>% Allocation</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <td>
                                                <div class="form-group mb-0">
                                                  <select class="form-control" id="" name="">
                                                    <option>Scheme Name</option>
                                                    <option>1</option>
                                                    <option>2</option>
                                                    <option>3</option>
                                                    <option>4</option>
                                                  </select>
                                                  <b class="schemeSelectNotch"></b>
                                                </div>
                                            </td>
                                            <td></td>
                                          </tr>
                                          <tr>
                                            <td>
                                                <div class="form-group mb-0">
                                                  <select class="form-control" id="" name="">
                                                    <option>Scheme Name</option>
                                                    <option>1</option>
                                                    <option>2</option>
                                                    <option>3</option>
                                                    <option>4</option>
                                                  </select>
                                                  <b class="schemeSelectNotch"></b>
                                                </div>
                                            </td>
                                            <td></td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                        
                                </div>
                                <div class="col-lg-3 addSchemeBtn">
                                    <div class="btn btn-primary add_button">Add Scheme</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12 text-center allSchemeBtn">
                            <button class="btn btn-success btn-sm savedBtn">Submit</button>
                            <button type="button" class="btn btn-success btn-sm downloadbtn">Download</button>
                            <button type="button" class="btn btn-success btn-sm savedBtn">Save</button>
                        </div>
                        
                        <div class="allocationChartAll">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="investmentPie">
                                        <img class="img-fluid" src="{{asset('')}}/f/images/pieChart.png" alt="" />
                                    </div>
                                </div>
                                <div class="col-lg-6 investmentAllocationCol">
                                    <div class="table-responsive">
                                        <table class="table investmentAllocationTable">
                                        <thead>
                                          <tr>
                                            <th>Asset</th>
                                            <th>Amount</th>
                                            <th>% Share</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <td>Debt</td>
                                            <td>23,022,782</td>
                                            <td>36%</td>
                                          </tr>
                                          <tr>
                                            <td>Equity</td>
                                            <td>28,258,258</td>
                                            <td>48%</td>
                                          </tr>
                                          <tr>
                                            <td>Gold</td>
                                            <td>7,022,782</td>
                                            <td>9%</td>
                                          </tr>
                                          <tr>
                                            <td>Other</td>
                                            <td>7,022,782</td>
                                            <td>7%</td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                        
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="myHolding myHoldingLeft investAllocTable">
                                    <div class="myHoldingTitle">
                                        <label class="allocationCheck"><div class="myHoldingTitleOnly">Category Holding</div>
                                          <input type="checkbox" checked="checked">
                                          <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table myHoldingTable mb-0">
                                        <thead>
                                          <tr>
                                            <th>Category</th>
                                            <th>Amount</th>
                                            <th>% Share</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <td>Infosis</td>
                                            <td>24,025,025</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Tata Consultancy Services Ltd. </td>
                                            <td>2,147,125</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>ICICI Bank Ltd</td>
                                            <td>24,025,025</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Housing Development Finance Corporat</td>
                                            <td>2,147,125</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Motherson Sumi Systems Ltd.</td>
                                            <td>24,025,025</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Infosis</td>
                                            <td>2,147,125</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Tata Consultancy Services Ltd. </td>
                                            <td>24,025,025</td>
                                            <td>9.67</td>
                                          </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                        
                                </div>
                                <div class="myHolding myHoldingLeft investAllocTable">
                                    <div class="myHoldingTitle">
                                        <label class="allocationCheck"><div class="myHoldingTitleOnly">Fund Manager Holding</div>
                                          <input type="checkbox" checked="checked">
                                          <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table myHoldingTable mb-0">
                                        <thead>
                                          <tr>
                                            <th>Fund Manager</th>
                                            <th>Amount</th>
                                            <th>% Share</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>24,025,025</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Kotak Bluechip Fund - Direct Plan - Growth</td>
                                            <td>2,147,125</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>24,025,025</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>2,147,125</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Kotak Bluechip Fund - Direct Plan - Growth</td>
                                            <td>24,025,025</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>2,147,125</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Kotak Bluechip Fund - Direct Plan - Growth</td>
                                            <td>24,025,025</td>
                                            <td>9.67</td>
                                          </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                        
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="myHolding myHoldingLeft investAllocTable">
                                    <div class="myHoldingTitle">
                                        <label class="allocationCheck"><div class="myHoldingTitleOnly">AMC Holding</div>
                                          <input type="checkbox" checked="checked">
                                          <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table myHoldingTable mb-0">
                                        <thead>
                                          <tr>
                                            <th>AMC</th>
                                            <th>Amount</th>
                                            <th>% Share</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>24,025,025</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Kotak Bluechip Fund - Direct Plan - Growth</td>
                                            <td>2,147,125</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>24,025,025</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>2,147,125</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Kotak Bluechip Fund - Direct Plan - Growth</td>
                                            <td>24,025,025</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>2,147,125</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Kotak Bluechip Fund - Direct Plan - Growth</td>
                                            <td>24,025,025</td>
                                            <td>9.67</td>
                                          </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                        
                                </div>
                                <div class="myHolding myHoldingLeft investAllocTable">
                                    <div class="myHoldingTitle">
                                        <label class="allocationCheck"><div class="myHoldingTitleOnly">Scheme Holding</div>
                                          <input type="checkbox" checked="checked">
                                          <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table myHoldingTable schemeTable mb-0">
                                            <thead>
                                              <tr>
                                                <th>Scheme</th>
                                                <th>Amount</th>
                                                <th>% Share</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                              <tr>
                                                <td>Scheme 1</td>
                                                <td>24,025,025</td>
                                                <td>9.67</td>
                                              </tr>
                                              <tr>
                                                <td>Scheme 2</td>
                                                <td>2,147,125</td>
                                                <td>9.67</td>
                                              </tr>
                                              <tr>
                                                <td>Scheme 3</td>
                                                <td>24,025,025</td>
                                                <td>9.67</td>
                                              </tr>
                                              <tr>
                                                <td>Scheme 4</td>
                                                <td>2,147,125</td>
                                                <td>9.67</td>
                                              </tr>
                                              <tr>
                                                <td>Scheme 5</td>
                                                <td>24,025,025</td>
                                                <td>9.67</td>
                                              </tr>
                                              <tr>
                                                <td>Scheme 6</td>
                                                <td>2,147,125</td>
                                                <td>9.67</td>
                                              </tr>
                                              <tr>
                                                <td>Scheme Category</td>
                                                <td>24,025,025</td>
                                                <td>59.67</td>
                                              </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                        
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="bar"></div>
                                <div class="allocationSegment">
                                    <label class="allocationCheck">
                                        <div class="allocationSegmentTitle">Equity Allocation</div>
                                        <input type="checkbox" checked="checked">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="myHolding myHoldingLeft investAllocTable">
                                    <div class="myHoldingTitle">
                                        <label class="allocationCheck"><div class="myHoldingTitleOnly">Top 20 Holding</div>
                                          <input type="checkbox" checked="checked">
                                          <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table myHoldingTable mb-0">
                                        <tbody>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Kotak Bluechip Fund - Direct Plan - Growth</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Kotak Bluechip Fund - Direct Plan - Growth</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Kotak Bluechip Fund - Direct Plan - Growth</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Kotak Bluechip Fund - Direct Plan - Growth</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Kotak Bluechip Fund - Direct Plan - Growth</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Kotak Bluechip Fund - Direct Plan - Growth</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Kotak Bluechip Fund - Direct Plan - Growth</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Kotak Bluechip Fund - Direct Plan - Growth</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Kotak Bluechip Fund - Direct Plan - Growth</td>
                                            <td>9.67%</td>
                                          </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                        
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="myHolding myHoldingLeft investAllocTable">
                                    <div class="myHoldingTitle">
                                        <label class="allocationCheck"><div class="myHoldingTitleOnly">Top 20 Sectors</div>
                                          <input type="checkbox" checked="checked">
                                          <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table myHoldingTable mb-0">
                                        <tbody>
                                          <tr>
                                            <td>Technology</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Financials</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>FMCG</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Technology</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Financials</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>FMCG</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Technology</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Financials</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>FMCG</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Technology</td>
                                            <td>9.67%</td>
                                          </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                        
                                </div>
                                
                                <div class="myHolding myHoldingLeft investAllocTable">
                                    <div class="myHoldingTitle">
                                        <label class="allocationCheck"><div class="myHoldingTitleOnly">Equity Classification</div>
                                          <input type="checkbox" checked="checked">
                                          <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table myHoldingTable mb-0">
                                        <tbody>
                                          <tr>
                                            <td>Large Cap</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Mid Cap</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Small Cap</td>
                                            <td>9.67%</td>
                                          </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                        
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="bar"></div>
                                <div class="allocationSegment">
                                    <label class="allocationCheck">
                                        <div class="allocationSegmentTitle">Debt Allocation</div>
                                        <input type="checkbox" checked="checked">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="myHolding myHoldingLeft investAllocTable">
                                    <div class="myHoldingTitle">
                                        <label class="allocationCheck"><div class="myHoldingTitleOnly">Top 20 Holding</div>
                                          <input type="checkbox" checked="checked">
                                          <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table myHoldingTable mb-0">
                                        <tbody>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Kotak Bluechip Fund - Direct Plan - Growth</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Kotak Bluechip Fund - Direct Plan - Growth</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Kotak Bluechip Fund - Direct Plan - Growth</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Kotak Bluechip Fund - Direct Plan - Growth</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Kotak Bluechip Fund - Direct Plan - Growth</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Kotak Bluechip Fund - Direct Plan - Growth</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Kotak Bluechip Fund - Direct Plan - Growth</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Kotak Bluechip Fund - Direct Plan - Growth</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Kotak Bluechip Fund - Direct Plan - Growth</td>
                                            <td>9.67%</td>
                                          </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                        
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="myHolding myHoldingLeft investAllocTable">
                                    <div class="myHoldingTitle">
                                        <label class="allocationCheck"><div class="myHoldingTitleOnly">Top Asset Typr</div>
                                          <input type="checkbox" checked="checked">
                                          <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table myHoldingTable mb-0">
                                        <tbody>
                                          <tr>
                                            <td>Technology</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Financials</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>FMCG</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Technology</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Financials</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>FMCG</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Technology</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Financials</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>FMCG</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Technology</td>
                                            <td>9.67%</td>
                                          </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                        
                                </div>
                                
                                <div class="myHolding myHoldingLeft investAllocTable">
                                    <div class="myHoldingTitle">
                                        <label class="allocationCheck"><div class="myHoldingTitleOnly">Debt Credit Quality</div>
                                          <input type="checkbox" checked="checked">
                                          <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table myHoldingTable mb-0">
                                        <tbody>
                                          <tr>
                                            <td>Sovereign</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>AAA</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>AA</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>A</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Unrated</td>
                                            <td>9.67%</td>
                                          </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                        
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="bar"></div>
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="allocationSegment">
                                    <label class="allocationCheck">
                                        <div class="allocationSegmentTitle">Gold Allocation</div>
                                        <input type="checkbox" checked="checked">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="myHolding myHoldingLeft investAllocTable">
                                    <div class="myHoldingTitle">
                                        <label class="allocationCheck"><div class="myHoldingTitleOnly">Top 5 Holding</div>
                                          <input type="checkbox" checked="checked">
                                          <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table myHoldingTable mb-0">
                                        <tbody>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Kotak Bluechip Fund - Direct Plan - Growth</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Kotak Bluechip Fund - Direct Plan - Growth</td>
                                            <td>9.67%</td>
                                          </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                        
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="allocationSegment">
                                    <label class="allocationCheck">
                                        <div class="allocationSegmentTitle">Other</div>
                                        <input type="checkbox" checked="checked">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="myHolding myHoldingLeft investAllocTable">
                                    <div class="myHoldingTitle">
                                        <label class="allocationCheck"><div class="myHoldingTitleOnly">Top 5 Holding</div>
                                          <input type="checkbox" checked="checked">
                                          <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table myHoldingTable mb-0">
                                        <tbody>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Kotak Bluechip Fund - Direct Plan - Growth</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>IDBI India Top 100 Equity Fund - Direct Plan</td>
                                            <td>9.67%</td>
                                          </tr>
                                          <tr>
                                            <td>Kotak Bluechip Fund - Direct Plan - Growth</td>
                                            <td>9.67%</td>
                                          </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                        
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="bar" style="background: none;margin-top: 6px;"></div>
                                <div class="allocationSegment">
                                    <label class="allocationCheck">
                                        <div class="allocationSegmentTitle">Past performance of Selected / Suggested Scheme</div>
                                        <input type="checkbox" checked="checked">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                
                                <div class="myHolding myHoldingLeft investAllocTable">
                                    <div class="table-responsive">
                                        <table class="table myHoldingTable mb-0 performanceTable">
                                        <thead>
                                          <tr>
                                            <th>Scheme Name</th>
                                            <th>
                                                <div class="allocationSegment">
                                                    <label class="allocationCheck">
                                                        <div class="">3 Month</div>
                                                        <input type="checkbox" checked="checked">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="allocationSegment">
                                                    <label class="allocationCheck">
                                                        <div class="">6 Month</div>
                                                        <input type="checkbox" checked="checked">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="allocationSegment">
                                                    <label class="allocationCheck">
                                                        <div class="">9 Month</div>
                                                        <input type="checkbox" checked="checked">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="allocationSegment">
                                                    <label class="allocationCheck">
                                                        <div class="">1 Year</div>
                                                        <input type="checkbox" checked="checked">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="allocationSegment">
                                                    <label class="allocationCheck">
                                                        <div class="">2 Year</div>
                                                        <input type="checkbox" checked="checked">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="allocationSegment">
                                                    <label class="allocationCheck">
                                                        <div class="">3 Year</div>
                                                        <input type="checkbox">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="allocationSegment">
                                                    <label class="allocationCheck">
                                                        <div class="">5 Year</div>
                                                        <input type="checkbox">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="allocationSegment">
                                                    <label class="allocationCheck">
                                                        <div class="">10 Year</div>
                                                        <input type="checkbox">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <td>Scheme 1</td>
                                            <td>9.67</td>
                                            <td>9.67</td>
                                            <td>9.67</td>
                                            <td>9.67</td>
                                            <td>9.67</td>
                                            <td>9.67</td>
                                            <td>9.67</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Scheme 2</td>
                                            <td>9.67</td>
                                            <td>9.67</td>
                                            <td>9.67</td>
                                            <td>9.67</td>
                                            <td>9.67</td>
                                            <td>9.67</td>
                                            <td>9.67</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Scheme 3</td>
                                            <td>9.67</td>
                                            <td>9.67</td>
                                            <td>9.67</td>
                                            <td>9.67</td>
                                            <td>9.67</td>
                                            <td>9.67</td>
                                            <td>9.67</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Scheme 4</td>
                                            <td>9.67</td>
                                            <td>9.67</td>
                                            <td>9.67</td>
                                            <td>9.67</td>
                                            <td>9.67</td>
                                            <td>9.67</td>
                                            <td>9.67</td>
                                            <td>9.67</td>
                                          </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                        
                                </div>
                            </div>
                            
                            <div class="col-md-12 text-center allSchemeBtn border-0 pb-0">
                                <button type="button" class="btn btn-success btn-sm downloadbtn">Download</button>
                                <button type="button" class="btn btn-success btn-sm savedBtn">Save</button>
                            </div>
                        </div>
                        
                        <div class="row mt-5">
                            <div class="col-md-12">
                                This report has been prepared on the basis of data available with us and we have taken all precautions so that there are no errors and lapses. However, we do not assume any liability for actions taken on the basis of this report. The user is advised to verify the contents of the report independently.
                            </div>
                            <div class="col-md-12">
                                Report Date : 29/11/2021
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/images/shape2.png" alt="" />
        </div>
    </section>

@endsection
