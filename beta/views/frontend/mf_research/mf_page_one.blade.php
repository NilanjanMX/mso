@extends('layouts.frontend')

@section('content')
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">MY PORTFOLIO</h2>
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
                        <h2 class="headline portfolioTitle">Aditya Birla SL Focused Equity Fund (G) - Regular Plan - Growth Option</h2>
                        <!-- <div class="rt-btn-prt">
                            <a href="{{route('frontend.samplereports')}}" target="_blank">Sample Reports</a>
                            <a href="{{route('frontend.how-to-use-calculator')}}" target="_blank">How to Use</a>
                        </div> -->
                        <div class="row mar-top">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table portfolioTopTable mb-0">
                                        <thead>
                                          <tr>
                                            <th>Category</th>
                                            <th>Fund Manager</th>
                                            <th>Benchmark</th>
                                            <th>NAV <br/>As On 15-Nov-2021</th>
                                            <th>AUM (in Rs Cr) <br/>As On 31-Nov-2021</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <td>Equity : Large Cap</td>
                                            <td>Mahesh Patil</td>
                                            <td>NIFTY 100 - TRI</td>
                                            <td></td>
                                            <td></td>
                                          </tr>
                                        </tbody>
                                      </table>
                                </div>
                                  
                                  <button type="button" class="btn btn-success btn-sm portfolioBtn">Download</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="myHolding myHoldingLeft">
                                    <div class="myHoldingTitle"><div class="myHoldingTitleOnly">Top Equity Holdings (15-Nov-2020)</div> <img class="img-fluid downloadImg" src="{{asset('')}}/f/images/download.png" alt="" /></div>
                                    <div class="table-responsive">
                                        <table class="table myHoldingTable mb-0">
                                        <thead>
                                          <tr>
                                            <th>Company</th>
                                            <th>Sector</th>
                                            <th>Holding %</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <td>Infosis</td>
                                            <td>Technology</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Tata Consultancy Services Ltd. </td>
                                            <td>Technology</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>ICICI Bank Ltd</td>
                                            <td>Financials</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Housing Development Finance Corporat</td>
                                            <td>Financials</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Motherson Sumi Systems Ltd.</td>
                                            <td>Automobile and Ancillaries</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Infosis</td>
                                            <td>Technology</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Tata Consultancy Services Ltd. </td>
                                            <td>Technology</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>ICICI Bank Ltd</td>
                                            <td>Financials</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Housing Development Finance Corporat</td>
                                            <td>Financials</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Tata Consultancy Services Ltd. </td>
                                            <td>Technology</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Motherson Sumi Systems Ltd.</td>
                                            <td>Automobile and Ancillaries</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Infosis</td>
                                            <td>Technology</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Tata Consultancy Services Ltd. </td>
                                            <td>Technology</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>ICICI Bank Ltd</td>
                                            <td>Financials</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Housing Development Finance Corporat</td>
                                            <td>Financials</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Motherson Sumi Systems Ltd.</td>
                                            <td>Automobile and Ancillaries</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Infosis</td>
                                            <td>Technology</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Tata Consultancy Services Ltd. </td>
                                            <td>Technology</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>ICICI Bank Ltd</td>
                                            <td>Financials</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Housing Development Finance Corporat</td>
                                            <td>Financials</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Motherson Sumi Systems Ltd.</td>
                                            <td>Automobile and Ancillaries</td>
                                            <td>9.67</td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                </div>
                                
                                <div class="myHolding myHoldingLeft">
                                    <div class="myHoldingTitle">Debt Holdings (As on holding date)</div>
                                    <div class="table-responsive">
                                      <table class="table myHoldingTable mb-0">
                                        <thead>
                                          <tr>
                                            <th>Instrument</th>
                                            <th>Asset Type</th>
                                            <th>Holding %</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <td>06.51% Karnataka SDL - 30-Dec-20</td>
                                            <td>Gvernment Securitie</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>National Highways Authority of India</td>
                                            <td>Corporate Dept</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>ICICI Bank Ltd</td>
                                            <td>Financials</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Housing Development Finance Corporat</td>
                                            <td>Financials</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Motherson Sumi Systems Ltd.</td>
                                            <td>Automobile and Ancillaries</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Infosis</td>
                                            <td>Technology</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Tata Consultancy Services Ltd. </td>
                                            <td>Technology</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>ICICI Bank Ltd</td>
                                            <td>Financials</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Housing Development Finance Corporat</td>
                                            <td>Financials</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>Motherson Sumi Systems Ltd.</td>
                                            <td>Automobile and Ancillaries</td>
                                            <td>9.67</td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                        
                                </div>
                                
                                <div class="myHolding myHoldingLeft">
                                    <div class="myHoldingTitle">Miscellaneous Holdings (As on holding date)</div>
                                    <div class="table-responsive">
                                        <table class="table myHoldingTable mb-0">
                                        <thead>
                                          <tr>
                                            <th>Instrument</th>
                                            <th>Asset Type</th>
                                            <th>Holding %</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <td>06.51% Karnataka SDL - 30-Dec-20</td>
                                            <td>Gvernment Securitie</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>National Highways Authority of India</td>
                                            <td>Corporate Dept</td>
                                            <td>9.67</td>
                                          </tr>
                                          <tr>
                                            <td>ICICI Bank Ltd</td>
                                            <td>Financials</td>
                                            <td>9.67</td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                        
                                </div>
                                
                                <div class="myHolding myHoldingLeft">
                                    <div class="myHoldingTitle">Cash & Cash Equilvalents Holdings (As on holding date)</div>
                                    <div class="table-responsive">
                                        <table class="table myHoldingTable mb-0">
                                    <thead>
                                      <tr>
                                        <th>Instrument</th>
                                        <th>Asset Type</th>
                                        <th>Holding %</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td>06.51% Karnataka SDL - 30-Dec-20</td>
                                        <td>Gvernment Securitie</td>
                                        <td>9.67</td>
                                      </tr>
                                      <tr>
                                        <td>National Highways Authority of India</td>
                                        <td>Corporate Dept</td>
                                        <td>9.67</td>
                                      </tr>
                                      <tr>
                                        <td>ICICI Bank Ltd</td>
                                        <td>Financials</td>
                                        <td>9.67</td>
                                      </tr>
                                    </tbody>
                                  </table>
                                    </div>
                                        
                                </div>
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="myHolding myHoldingRight">
                                    <div class="myHoldingTitle">Portfolio Market Cap</div>
                                    <div class="table-responsive">
                                        <table class="table myHoldingTable mb-0">
                                    <thead>
                                      <tr>
                                        <th></th>
                                        <th>Fund</th>
                                        <th>Category Avg</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td>Avg Mkt Cap (Cr)</td>
                                        <td>216.23</td>
                                        <td>8.7487</td>
                                      </tr>
                                      <tr>
                                        <td>Large Cap (%)</td>
                                        <td>118.96</td>
                                        <td>44.65%</td>
                                      </tr>
                                      <tr>
                                        <td>Mid Cap (%)</td>
                                        <td>255.61</td>
                                        <td>40.2%</td>
                                      </tr>
                                      <tr>
                                        <td>Small Cap (%)</td>
                                        <td>255.61</td>
                                        <td>40.2%</td>
                                      </tr>
                                    </tbody>
                                  </table>
                                    </div>
                                        
                                </div>
                                
                                <div class="myHolding myHoldingRight">
                                    <div class="myHoldingTitle">Debt Profile</div>
                                    <div class="table-responsive">
                                        <table class="table myHoldingTable mb-0">
                                    <thead>
                                      <tr>
                                        <th></th>
                                        <th>Fund</th>
                                        <th>Category Avg</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td>No. of Securities</td>
                                        <td>216.23</td>
                                        <td>2</td>
                                      </tr>
                                      <tr>
                                        <td>Mod Duration</td>
                                        <td>118.96</td>
                                        <td>7</td>
                                      </tr>
                                      <tr>
                                        <td>Avg Maturity</td>
                                        <td>255.61</td>
                                        <td>49</td>
                                      </tr>
                                      <tr>
                                        <td>YTM (%)</td>
                                        <td>255.61</td>
                                        <td>13.4</td>
                                      </tr>
                                    </tbody>
                                  </table>
                                    </div>
                                        
                                </div>
                                
                                <div class="myHolding myHoldingRight">
                                    <div class="myHoldingTitle" style="border-bottom: 0px;">Portfolio Summary</div>
                                    <div class="table-responsive">
                                        <table class="table myHoldingTable mb-0">
                                    <tbody>
                                      <tr>
                                        <td>No. of Stocks</td>
                                        <td>2</td>
                                      </tr>
                                      <tr>
                                        <td>Top 5 stock (%)</td>
                                        <td>7</td>
                                      </tr>
                                      <tr>
                                        <td>Top 10 stock (%)</td>
                                        <td>7</td>
                                      </tr>
                                      <tr>
                                        <td>Portfolio P/B Ratio</td>
                                        <td>49</td>
                                      </tr>
                                      <tr>
                                        <td>Portfolio P/E Ratio</td>
                                        <td>13.4</td>
                                      </tr>
                                    </tbody>
                                  </table>
                                    </div>
                                        
                                </div>
                                
                                <div class="myHolding myHoldingRight">
                                    <div class="myHoldingTitle">Top Sectors</div>
                                    <div class="table-responsive">
                                        <table class="table myHoldingTable mb-0">
                                    <thead>
                                      <tr>
                                        <th>Sector Name</th>
                                        <th>% Assets</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td>Sector 1</td>
                                        <td>2</td>
                                      </tr>
                                      <tr>
                                        <td>Sector 2</td>
                                        <td>7</td>
                                      </tr>
                                      <tr>
                                        <td>Sector 3</td>
                                        <td>49</td>
                                      </tr>
                                      <tr>
                                        <td>Sector 4</td>
                                        <td>13.4</td>
                                      </tr>
                                      <tr>
                                        <td>Sector 5</td>
                                        <td>14</td>
                                      </tr>
                                    </tbody>
                                  </table>
                                    </div>
                                        
                                </div>
                                
                                <div class="myHolding myHoldingRight">
                                    <div class="myHoldingTitle">Debt Credit Quality</div>
                                    <div class="table-responsive">
                                        <table class="table myHoldingTable mb-0">
                                    <thead>
                                      <tr>
                                        <th>Sector Name</th>
                                        <th>% Assets</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td>Sovereign</td>
                                        <td>2</td>
                                      </tr>
                                      <tr>
                                        <td>AAA</td>
                                        <td>7</td>
                                      </tr>
                                      <tr>
                                        <td>AA</td>
                                        <td>49</td>
                                      </tr>
                                      <tr>
                                        <td>A</td>
                                        <td>13.4</td>
                                      </tr>
                                      <tr>
                                        <td>Unrated</td>
                                        <td>14</td>
                                      </tr>
                                    </tbody>
                                  </table>
                                    </div>
                                        
                                </div>
                                
                                <div class="myHolding myHoldingRight">
                                    <div class="myHoldingTitle">Trailing Return</div>
                                    <div class="table-responsive">
                                        <table class="table myHoldingTable mb-0">
                                        <thead>
                                          <tr>
                                            <th></th>
                                            <th>Fund</th>
                                            <th>Category Avg</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <td>3 Month</td>
                                            <td>258.2</td>
                                            <td>2</td>
                                          </tr>
                                          <tr>
                                            <td>6 Month</td>
                                            <td>158.2</td>
                                            <td>7</td>
                                          </tr>
                                          <tr>
                                            <td>1 Year</td>
                                            <td>258.2</td>
                                            <td>49</td>
                                          </tr>
                                          <tr>
                                            <td>3 Year</td>
                                            <td>245</td>
                                            <td>13.4</td>
                                          </tr>
                                          <tr>
                                            <td>5 Year</td>
                                            <td>158</td>
                                            <td>14</td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                        
                                </div>
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
