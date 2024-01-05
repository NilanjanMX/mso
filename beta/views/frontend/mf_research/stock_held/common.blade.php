<style>
    .blockstab {
        display: inline-block;
        width: 219px;
        padding: 0 2px;
    }
    .blockstab a {
        padding: 7px 2px !important;
        font-size: 12px !important;
    }
</style>

<div class="" id="myTab" role="tablist" style="margin-top: 20px; text-align: center;">
    <div class="blockstab">
        <div class="form-group">
            <a href="{{route('frontend.MFStocksHeld')}}" class="btn <?php echo ($activemenu=="stocks_held")?'btn-success fund_active_btn':'btn-warning fund_norm_btn';?>" style="width: 100%;">
                Stocks Held by Mutual Funds
            </a>
        </div>  
    </div>

    <div class="blockstab">
        <div class="form-group">
            <a href="{{route('frontend.stocks_attracting_fund_managers')}}" class="btn <?php echo ($activemenu=="stocks_attracting_fund_managers")?'btn-success fund_active_btn':'btn-warning fund_norm_btn';?>" style="width: 100%;">
                Stocks Attracting Fund Managers
            </a>
        </div>  
    </div>

    <div class="blockstab">
        <div class="form-group">
            <a href="{{route('frontend.stocks_seeing_selling_pressure')}}" class="btn <?php echo ($activemenu=="stocks_seeing_selling_pressure")?'btn-success fund_active_btn':'btn-warning fund_norm_btn';?>" style="width: 100%;">
                Stocks Seeing Selling Pressure
            </a>
        </div>  
    </div>

    <div class="blockstab">
        <div class="form-group">
            <a href="{{route('frontend.mf_stocks_bought')}}" class="btn <?php echo ($activemenu=="stocks_bought")?'btn-success fund_active_btn':'btn-warning fund_norm_btn';?>" style="width: 100%;">
                New Stocks Bought
            </a>
        </div>  
    </div>

    <div class="blockstab">
        <div class="form-group">
            <a href="{{route('frontend.mf_stocks_completely_exited')}}" class="btn <?php echo ($activemenu=="stocks_completely_exited")?'btn-success fund_active_btn':'btn-warning fund_norm_btn';?>" style="width: 100%;">
                Stocks Completely Exited
            </a>
        </div>  
    </div>
</div>